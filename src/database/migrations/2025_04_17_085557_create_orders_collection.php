<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersCollection extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('orders', function ($collection) {
            $collection->index('client_id');
            $collection->index('status');
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->drop('orders');
    }
}
