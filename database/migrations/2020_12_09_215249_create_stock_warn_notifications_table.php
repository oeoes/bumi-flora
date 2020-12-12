<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockWarnNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_warn_notifications', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string('title');
            $table->text('body');
            $table->tinyInteger('is_read')->default(0);
            $table->tinyInteger('urgency')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_warn_notifications');
    }
}
