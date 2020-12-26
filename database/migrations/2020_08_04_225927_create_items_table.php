<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignId('unit_id');
            $table->foreignId('brand_id')->nullable();
            $table->foreignId('stake_holder_id')->nullable();
            $table->foreignId('category_id');
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('base_unit');
            $table->integer('base_unit_conversion');
            $table->string('cabinet')->nullable(); # rak
            $table->text('description')->nullable();
            $table->string('main_cost')->nullable(); # harga pokok
            $table->integer('price')->nullable(); # harga jual
            $table->string('barcode')->unique();
            $table->string('item_code')->nullable();
            $table->integer('min_stock')->default(0);
            $table->tinyInteger('published')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
        
    }
}
