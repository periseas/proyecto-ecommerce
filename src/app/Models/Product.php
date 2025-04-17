<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;


class Product extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'products';

    protected $fillable = [
        'title',
        'price',
        'description',
        'category',
        'image',
        'rating'
    ];

    protected $casts = [
        'rating' => 'array'
    ];
}
