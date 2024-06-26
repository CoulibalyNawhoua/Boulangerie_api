<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use App\Core\Traits\UuidGenerator;
use App\Core\Traits\GetModelByUuid;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    use UuidGenerator;
    use GetModelByUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'last_name',
        'first_name',
        'password',
        'bakehouse_id',
        'phone',
        'active',
        'uuid'
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

     /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }

    public function bakehouse()
    {
        return $this->belongsTo(Bakehouse::class, 'bakehouse_id', 'id');
    }
    public function livraisons(){
        return $this->hasMany(Delivery::class, 'delivery_person_id', 'id');
    }

    public function retours(){
        return $this->hasMany(OrderReturn::class, 'delivery_person_id', 'id');
    }

    public function transactions(){
        return $this->hasMany(Transaction::class, 'delivery_person_id', 'id');
    }

    public function abilityList(){

        $permissions = $this->roles()->with('permissions')->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->toArray();

        return $permissions;
    }
}
