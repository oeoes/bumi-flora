<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStorageRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storage_records', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->uuid('item_id');
            $table->string('transaction_no');
            $table->enum('dept', ['gudang', 'utama']);
            $table->text('description')->nullable();
            $table->integer('amount_in')->nullable();
            $table->integer('amount_out')->nullable();
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
        Schema::dropIfExists('storage_records');
    }
}
