<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::raw(function ($collection) {
            return $collection->aggregate([
                ['$sample' => ['size' => 6]]
            ]);
        });
        return response()->json([
            'products' => $products
        ]);
    }
}
