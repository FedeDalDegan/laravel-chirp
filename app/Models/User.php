<?php

namespace App\Models;

# use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable /*implements MustVerifyEmail*/ # Si activamos esto, debemos DESCOMENTAR la linea 5. La cual activa la verificacion de email.
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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

    # Creamos una relacion entre el usuario y el chirp.
    # Un usuario puede tener MULTIPLES chirps.
    # Cada vez que tengamos un usuario, nos devolvera todos sus chirps que esten en la BDD.. $user->chirps
    public function chirps(): HasMany{
        return $this->hasMany(Chirp::class); # Le pasamos el MODELO de chirp.
    }
}
