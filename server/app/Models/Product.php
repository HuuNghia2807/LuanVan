<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = "products";

    protected $primarykey = "id";

    protected $fillable = [
        'product_name',
        'product_code',
        'product_price',
        'product_rating',
        'category_id',
        'discount_id'
    ];

    public function get_category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
