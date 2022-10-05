<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $table = "product_images";

    protected $primarykey = "id";

    protected $fillable = [
        'product_image_name',
        'product_image_link',
        'product_id'
    ];
}
