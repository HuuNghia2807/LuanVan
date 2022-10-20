<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    protected $table = "users_detail";

    protected $primarykey = "id";

    protected $fillable = [
        'user_first_name',
        'user_last_name',
        'user_phone',
        'user_birth',
        'user_gender',
        'user_avatar'
    ];
}
