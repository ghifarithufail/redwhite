<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Hrd\Biodata;
use App\Models\Hrd\Pengemudi;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'status',
        'role_id',
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

    // public function role()
    // {
    //     return $this->belongsTo(Role::class, 'role_id','id');
    // }

    // public function hasRole($role)
    // {
    //     return $this->roles->contains('name', $role);
    // }

    // public function getRoles()
    // {
    //     return $this->roles()->pluck('name')->toArray();
    // }

    public function biodata(): HasOne
    {
        return $this->hasOne(Biodata::class);
    }

    public function pengemudis()
    {
        return $this->belongsTo(Pengemudi::class);
    }

}
