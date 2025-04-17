<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;
use App\Models\Product;

class ImportFakeStoreProducts implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function handle(): void
    {
        $response = Http::get('https://fakestoreapi.com/products');

        if ($response->successful()) {
            foreach ($response->json() as $product) {
                Product::updateOrCreate(
                    ['title' => $product['title']],
                    [
                        'price' => $product['price'],
                        'description' => $product['description'],
                        'category' => $product['category'],
                        'image' => $product['image'],
                        'rating' => $product['rating'] ?? [],
                    ]
                );
            }
        }
    }
}
