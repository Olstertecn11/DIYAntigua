<?php

namespace App\Services\Affiliates;

use App\Models\AfiliadoInfo;
use App\Models\User;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReferralTracker
{
    private const SESSION_KEY = 'afiliado_referido';

    public function capture(Request $request): void
    {
        $ref = trim((string) $request->query('ref'));

        if ($ref === '') {
            return;
        }

        $affiliate = $this->findAffiliate($ref);

        if (! $affiliate) {
            return;
        }

        $request->session()->put(self::SESSION_KEY, [
            'ref' => $ref,
            'socio_id' => $affiliate->user_id,
            'captured_at' => now()->toIso8601String(),
        ]);
    }

    public function currentSocioId(Request $request): ?int
    {
        $payload = $request->session()->get(self::SESSION_KEY);

        if (! is_array($payload) || empty($payload['socio_id'])) {
            return null;
        }

        $affiliate = AfiliadoInfo::query()
            ->where('user_id', $payload['socio_id'])
            ->where('activo', true)
            ->first();

        return $affiliate?->user_id;
    }

    public function clear(Request $request): void
    {
        $request->session()->forget(self::SESSION_KEY);
    }

    public function referralCodeFor(User $user): string
    {
        $affiliate = $user->afiliadoInfo;

        if ($affiliate?->codigo_referido) {
            return $affiliate->codigo_referido;
        }

        $code = $this->hashids()->encode($user->id);

        if (! $affiliate) {
            return $code;
        }

        if (AfiliadoInfo::where('codigo_referido', $code)->where('user_id', '!=', $user->id)->exists()) {
            $code = Str::upper(Str::random(10));
        }

        $affiliate->forceFill(['codigo_referido' => $code])->save();

        return $code;
    }

    public function commissionFor(?int $socioId, float $amount): float
    {
        if (! $socioId || $amount <= 0) {
            return 0.0;
        }

        $affiliate = AfiliadoInfo::query()
            ->where('user_id', $socioId)
            ->where('activo', true)
            ->first();

        if (! $affiliate) {
            return 0.0;
        }

        return round($amount * ((float) $affiliate->comision_porcentaje / 100), 2);
    }

    private function findAffiliate(string $ref): ?AfiliadoInfo
    {
        $affiliate = AfiliadoInfo::query()
            ->where('codigo_referido', $ref)
            ->where('activo', true)
            ->first();

        if ($affiliate) {
            return $affiliate;
        }

        $decoded = $this->hashids()->decode($ref);
        $userId = $decoded[0] ?? null;

        if (! $userId) {
            return null;
        }

        return AfiliadoInfo::query()
            ->where('user_id', $userId)
            ->where('activo', true)
            ->first();
    }

    private function hashids(): Hashids
    {
        return new Hashids(config('app.key') ?: 'diyantigua-referrals', 8);
    }
}
