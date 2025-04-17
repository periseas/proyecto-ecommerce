<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Jobs\ImportFakeStoreProducts;

class FakeStoreSeeder extends Seeder
{
    public function run(): void
    {
        ImportFakeStoreProducts::dispatchSync(); 
    }
}
