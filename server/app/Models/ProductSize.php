<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    use HasFactory;

    protected $table = "product_sizes";

    protected $primarykey = "id";

    protected $fillable = [
        'product_size_quantity',
        'product_id',
        'size_id'
    ];
}
