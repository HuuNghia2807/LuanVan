<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table="category";

    protected $primarykey="category_id";

    protected $fillable=[
        "category_name"
    ];
}
