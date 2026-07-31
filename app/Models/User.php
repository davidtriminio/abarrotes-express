<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Observers\UsuarioObserver;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, softDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'telefono',
        'password_reset_token',
        'token_expires_at',
        'recovery_key',
        'recovery_key_created_at',
        'email_verified_at'
    ];

    // Agregar un mutador para el campo `recovery_key`
    public static function booted()
    {
        static::creating(function ($user) {
            // Si el recovery_key no se ha proporcionado, generarlo
            if (empty($user->recovery_key)) {
                $user->recovery_key = Str::random(30);
            }
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'password_reset_token',
        'token_expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function ordenes(): HasMany
    {
        return $this->hasMany(Orden::class, 'user_id');
    }

    public function cupones()
    {
        return $this->hasMany(Cupon::class, 'user_id');
    }

    public function favoritos()
    {
        return $this->hasMany(Favorito::class);
    }

    public static function cleanExpiredTokens()
    {

        return self::where('token_expires_at', '<=', now())
            ->update([
                'password_reset_token' => null,
                'token_expires_at' => null,
            ]);
    }




    public $user;
    public $isAdmin;


    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->hasPermissionTo('ver:admin') == true){
            return true;
        }
        return false;
    }
}
