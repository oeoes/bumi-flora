<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemInsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_ins', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->uuid('item_id');
            $table->string('transaction_no');
            $table->enum('dept', ['gudang', 'utama']);
            $table->text('description')->nullable();
            $table->integer('amount');
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
        Schema::dropIfExists('item_ins');
    }
}
