<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sizes extends Model
{
    use HasFactory;

    protected $table="sizes";

    protected $primarykey="size_id ";

    protected $fillable=[
        'size',
    ];
}
