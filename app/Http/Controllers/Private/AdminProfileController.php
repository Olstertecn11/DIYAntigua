<?php

namespace App\Http\Controllers\Private;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Support\PhoneNumber;

class AdminProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user()->load('afiliadoInfo');

        return view('admin.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'telefono_country_code' => ['nullable', 'required_with:telefono_national', 'string', 'in:' . implode(',', array_keys(config('phone.countries', [])))],
            'telefono_national' => ['nullable', 'string', 'max:30', 'regex:/^[0-9\s().-]{5,30}$/'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'nombre_comercial' => [Rule::requiredIf($user->role_id == config('constantes.idAffiliate')), 'nullable', 'string', 'max:255'],
            'nit' => ['nullable', 'string', 'max:80'],
            'telefono_negocio_country_code' => ['nullable', 'required_with:telefono_negocio_national', 'string', 'in:' . implode(',', array_keys(config('phone.countries', [])))],
            'telefono_negocio_national' => ['nullable', 'string', 'max:30', 'regex:/^[0-9\s().-]{5,30}$/'],
            'direccion_negocio' => ['nullable', 'string', 'max:255'],
            'metodo_pago' => ['nullable', 'string', 'max:120'],
            'titular_pago' => ['nullable', 'string', 'max:160'],
            'cuenta_pago' => ['nullable', 'string', 'max:191'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telefono' => PhoneNumber::format($validated['telefono_country_code'] ?? null, $validated['telefono_national'] ?? null),
            'direccion' => $validated['direccion'] ?? null,
        ]);

        if ($user->role_id == config('constantes.idAffiliate') && $user->afiliadoInfo) {
            $user->afiliadoInfo->update([
                'nombre_comercial' => $validated['nombre_comercial'],
                'nit' => $validated['nit'] ?? null,
                'telefono_negocio' => PhoneNumber::format($validated['telefono_negocio_country_code'] ?? null, $validated['telefono_negocio_national'] ?? null),
                'direccion' => $validated['direccion_negocio'] ?? null,
                'metodo_pago' => $validated['metodo_pago'] ?? null,
                'titular_pago' => $validated['titular_pago'] ?? null,
                'cuenta_pago' => $validated['cuenta_pago'] ?? null,
            ]);
        }

        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    public function password(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }
}
