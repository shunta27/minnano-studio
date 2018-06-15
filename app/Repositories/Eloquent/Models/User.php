<?php

namespace App\Repositories\Eloquent\Models;

use App\Defines\User\Role;
use App\Repositories\Eloquent\Models\UserInformation;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function user_information()
    {
        return $this->hasOne(UserInformation::class);
    }

    public function isSystem(): bool
    {
        return $this->role == Role::SYSTEM()->valueOf();
    }

    public function isAdmin(): bool
    {
       return ($this->role > Role::DEFAULT()->valueOf() && $this->role <= Role::ADMIN()->valueOf());
    }

    public function isUser(): bool
    {
        return ($this->role > Role::DEFAULT()->valueOf() && $this->role <= Role::USER()->valueOf());
    }
}
