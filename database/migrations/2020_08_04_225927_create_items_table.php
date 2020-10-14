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
            $table->string('cabinet')->nullable(); # rak
            $table->tinyInteger('sale_status')->default(1);
            $table->text('description')->nullable();
            $table->string('main_cost'); # harga pokok
            $table->string('barcode')->unique();
            $table->integer('price'); # harga jual
            $table->integer('min_stock')->default(0);
            $table->timestamps();

            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('stake_holder_id')->references('id')->on('stake_holders')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
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
