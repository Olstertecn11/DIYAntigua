<?php

namespace App\Models\Concerns;

use Hashids\Hashids;

trait UsesHashidRouteKey
{
    public function getRouteKey(): mixed
    {
        return $this->hashids()->encode($this->getKey());
    }

    public function resolveRouteBinding($value, $field = null): mixed
    {
        if ($field) {
            return $this->where($field, $value)->first();
        }

        $decoded = $this->hashids()->decode((string) $value);
        $id = $decoded[0] ?? null;

        if (! $id && is_numeric($value)) {
            $id = (int) $value;
        }

        return $id ? $this->whereKey($id)->first() : null;
    }

    private function hashids(): Hashids
    {
        return new Hashids(config('app.key') . '|' . static::class, 10);
    }
}
