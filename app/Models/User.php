<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $guarded = ['id'];

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
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'is_admin',
        'cans'
    ];

    //Se crea el atributo is_admin donde el rol con id 1 es admin
    public function getIsAdminAttribute()
    {
        return $this->role_id === 1; // admin
    }

     //Un usuario solo puede tener 1 rol
     public function role()
     {
         return $this->belongsTo(Role::class, 'role_id');
     }
    
    public function getCansAttribute()
    {
        $cans = array();
        $permissions = null;
        if ($this->is_admin) 
        {
            $permissions = Permission::select('id', 'nombre')->get();
        } else 
        {
           /* $permissions = $this->role->permissions()
                ->get();
                */
           $permissions = RolesPermission::select('permissions.id','permissions.nombre')
           ->join('permissions','roles_permissions.permission_id','permissions.id')
           ->where('roles_permissions.role_id','=', $this->role_id)
           ->get();
        }

        foreach ($permissions as $permission) 
        {
            $cans[$permission->nombre] = $this->can($permission->nombre);
        }
        return $cans;
    }

    public function hasPermission(Int $idPermission) //este comprueba si pueden o no usar el permiso
    {
        return $this->role->permissions()->where('permissions.id', $idPermission)->exists();

    }
   

}
