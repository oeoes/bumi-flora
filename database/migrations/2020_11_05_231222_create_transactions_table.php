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
            $table->foreignId('payment_method_id')->constrained();
            $table->foreignId('payment_type_id')->constrained();
            $table->integer('discount');
            $table->integer('qty');
            $table->integer('additional_fee');
            $table->integer('tax');
            $table->time('transaction_time');
            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade')->onUpdate('cascade');
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
