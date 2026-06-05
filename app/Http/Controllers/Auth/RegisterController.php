<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\PhoneNumber;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return Inertia::render('Auth/Register', [
            'countries' => collect(config('phone.countries', []))
                ->map(fn (array $country, string $code) => [
                    'code' => $code,
                    'name' => $country['name'],
                    'dial' => $country['dial'],
                ])
                ->values()
                ->all(),
            'urls' => [
                'register' => route('register'),
                'login' => route('login'),
                'home' => route('welcome'),
            ],
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'telefono_country_code' => ['nullable', 'required_with:telefono_national', 'string', 'in:' . implode(',', array_keys(config('phone.countries', [])))],
            'telefono_national' => ['nullable', 'string', 'max:30', 'regex:/^[0-9\s().-]{5,30}$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'telefono' => PhoneNumber::format($data['telefono_country_code'] ?? null, $data['telefono_national'] ?? null),
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole('cliente');

        return $user;
    }
}
