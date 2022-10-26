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
        'order_note',
        'order_time',
        'receive_time',
        'payment_id',
        'address_id',
        'order_status_id',
        'customer_id',
        'employee_id',
    ];
}
