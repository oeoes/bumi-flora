<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignId('user_id')->constrained(); // kasir id
            $table->uuid('item_id');
            $table->uuid('stake_holder_id')->default('umum');
            $table->string('transaction_number');
            $table->enum('dept', ['ecommerce', 'utama']);
            $table->foreignId('payment_method_id')->nullable();
            $table->foreignId('payment_type_id')->nullable();
            $table->integer('discount');
            $table->integer('discount_item')->default(0);
            $table->integer('discount_customer')->default(0);
            $table->integer('qty');
            $table->integer('additional_fee');
            $table->integer('tax');
            $table->time('transaction_time');
            $table->tinyInteger('daily_complete')->default(0);
            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('payment_type_id')->references('id')->on('payment_types')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
