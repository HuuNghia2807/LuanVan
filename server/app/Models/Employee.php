<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'employees';
    protected $primaryKey = 'id';
    protected $fillable = [
        'email',
        'password',
        'role_id',
        'user_detail_id'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
