<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $primaryKey = 'id';

    protected $fillable = [
        'order_total_price',
        'order_status',
        'order_note',
        'order_time_order',
        'order_time_receive',
        'address_id',
        'payment_id',
        'user_id',
    ];
}
