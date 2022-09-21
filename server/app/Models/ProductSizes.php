<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSizes extends Model
{
    use HasFactory;

    protected $table="product_sizes";

    protected $primarykey="product_size_id ";

    protected $fillable=[
        'product_size_quantity',
    ];
}
