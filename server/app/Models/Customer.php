<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $primaryKey = 'id';

    protected $fillable = [
        'customer_name',
        'customer_phone',
        'customer_email	',
        'customer_password',
        'customer_birth',
        'customer_gender',
        'customer_avata',
    ];
}
