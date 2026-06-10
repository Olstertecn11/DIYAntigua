<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Support\PhoneNumber;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();
        $phone = PhoneNumber::split($user->telefono);

        return Inertia::render('Profile/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar_base64' => $user->avatar_base64,
                'telefono_country_code' => $phone['country'],
                'telefono_national' => $phone['number'],
                'direccion' => $user->direccion,
            ],
            'countries' => config('phone.countries', []),
            'urls' => [
                'update' => route('profile.update'),
                'password' => route('profile.password'),
            ],
        ]);
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
            'avatar_base64' => ['nullable', 'string', 'max:650000', 'regex:/^data:image\/(png|jpe?g|webp|gif);base64,[A-Za-z0-9+\/=]+$/'],
        ]);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telefono' => PhoneNumber::format($validated['telefono_country_code'] ?? null, $validated['telefono_national'] ?? null),
            'direccion' => $validated['direccion'] ?? null,
            'avatar_base64' => $validated['avatar_base64'] ?? null,
        ])->save();

        return back()->with('success', 'Tu perfil fue actualizado.');
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

        return back()->with('success', 'Tu contraseña fue actualizada.');
    }
}
