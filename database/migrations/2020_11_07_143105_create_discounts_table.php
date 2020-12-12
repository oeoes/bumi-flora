<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('promo_name');
            $table->enum('promo_target', ['customer', 'item']);
            $table->foreignId('stake_holder_id')->nullable(); // untuk promo target customer
            $table->enum('promo_item_type', ['item', 'category'])->nullable();
            $table->uuid('item_id')->nullable(); // untuk promo_item_type item
            $table->foreignId('category_id')->nullable(); // untuk promo_item_type category
            $table->integer('value');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('stake_holder_id')->references('id')->on('stake_holders')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discounts');
    }
}
