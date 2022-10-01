<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $table="products";

    protected $primarykey="product_id";

    protected $fillable=[
        'product_name',
        'product_price',
        'product_rating',
        'category_id',
        'created_at',
        'update_at',
    ];

    public function get_category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }
}
