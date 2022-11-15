<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'orders_detail';

    protected $primaryKey = 'id';

    protected $fillable = [
        'product_quantity',
        'product_size_id',
        'sale',
        'order_id',
    ];
}
