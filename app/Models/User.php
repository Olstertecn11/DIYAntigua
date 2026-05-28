<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\AfiliadoInfo;

// Agregamos 'role_id' al Fillable para poder guardarlo
#[Fillable(['name', 'email', 'telefono', 'direccion', 'password', 'role_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Relación con el modelo Role.
     * Un usuario pertenece a un Rol.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }



    public function isAdmin(): bool
    {
        return $this->role_id == config('constantes.idAdmin');
    }

    public function afiliadoInfo(): HasOne
    {
        return $this->hasOne(AfiliadoInfo::class, 'user_id');
    }

    public function reservaciones(): HasMany
    {
        return $this->hasMany(Reservacion::class, 'user_id');
    }
}
