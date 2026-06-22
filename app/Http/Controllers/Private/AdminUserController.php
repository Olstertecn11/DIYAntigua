<?php

namespace App\Http\Controllers\Private;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class AdminUserController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim((string) $request->query('search', ''));
        $role = trim((string) $request->query('role', ''));

        $users = User::query()
            ->with('roles:id,name,slug')
            ->withCount('reservaciones')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('telefono', 'like', "%{$search}%");
                });
            })
            ->when($role !== '', fn ($query) => $query->whereHas('roles', fn ($roles) => $roles->where('slug', $role)))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Admin/Usuarios/Index', [
            'users' => $users->through(fn (User $user) => $this->formatUser($user)),
            'roles' => $this->manageableRoles(),
            'filters' => [
                'search' => $search,
                'role' => $role,
            ],
            'urls' => [
                'index' => route('admin.usuarios.index'),
                'store' => route('admin.usuarios.store'),
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'role' => ['required', Rule::in($this->manageableRoleSlugs())],
        ]);

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => Str::lower($validated['email']),
                'telefono' => $validated['telefono'] ?? null,
                'direccion' => $validated['direccion'] ?? null,
                'password' => Hash::make(Str::random(48)),
            ]);

            $user->assignRole($validated['role']);

            return $user;
        });

        $mailSent = $this->sendInvitation($user->fresh('roles'), 'created');

        return back()->with(
            $mailSent ? 'success' : 'error',
            $mailSent
                ? 'Usuario creado e invitacion enviada correctamente.'
                : 'Usuario creado, pero no se pudo enviar el correo de invitacion.'
        );
    }

    public function update(Request $request, User $usuario): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($usuario->id)],
            'telefono' => ['nullable', 'string', 'max:30'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'role' => ['required', Rule::in($this->manageableRoleSlugs())],
        ]);

        $role = Role::where('slug', $validated['role'])->firstOrFail();

        $usuario->forceFill([
            'name' => $validated['name'],
            'email' => Str::lower($validated['email']),
            'telefono' => $validated['telefono'] ?? null,
            'direccion' => $validated['direccion'] ?? null,
        ])->save();

        $usuario->roles()->sync([$role->id]);

        return back()->with('success', 'Usuario actualizado correctamente.');
    }

    public function invite(User $usuario): RedirectResponse
    {
        $mailSent = $this->sendInvitation($usuario->fresh('roles'), 'resent');

        return back()->with(
            $mailSent ? 'success' : 'error',
            $mailSent
                ? 'Invitacion reenviada correctamente.'
                : 'No se pudo reenviar la invitacion. Revisa la configuracion de correo.'
        );
    }

    private function sendInvitation(User $user, string $reason): bool
    {
        $token = Password::broker()->createToken($user);
        $resetUrl = route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]);

        try {
            Mail::send('emails.admin.user-created', [
                'user' => $user,
                'resetUrl' => $resetUrl,
                'loginUrl' => $user->hasRole('admin') ? route('admin.login') : route('login'),
                'reason' => $reason,
                'roles' => $user->roles->pluck('name')->values()->all(),
            ], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                    ->subject('Tu usuario de DYANTIGUA fue creado');
            });

            return true;
        } catch (\Throwable $exception) {
            Log::warning('No se pudo enviar invitacion de usuario.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    private function formatUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'telefono' => $user->telefono,
            'direccion' => $user->direccion,
            'created_at' => $user->created_at?->format('d/m/Y'),
            'reservaciones_count' => $user->reservaciones_count,
            'roles' => $user->roles->map(fn (Role $role) => [
                'id' => $role->id,
                'name' => $role->name,
                'slug' => $role->slug,
            ])->values()->all(),
            'primary_role' => $user->roles->first()?->slug,
            'urls' => [
                'update' => route('admin.usuarios.update', $user),
                'invite' => route('admin.usuarios.invite', $user),
            ],
        ];
    }

    private function manageableRoles(): array
    {
        return Role::whereIn('slug', $this->manageableRoleSlugs())
            ->orderByRaw("case when slug = 'cliente' then 1 when slug = 'admin' then 2 else 3 end")
            ->get(['id', 'name', 'slug'])
            ->map(fn (Role $role) => [
                'id' => $role->id,
                'name' => $role->name,
                'slug' => $role->slug,
            ])
            ->values()
            ->all();
    }

    private function manageableRoleSlugs(): array
    {
        return ['cliente', 'admin'];
    }
}
