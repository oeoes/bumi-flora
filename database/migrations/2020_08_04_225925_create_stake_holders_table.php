<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStakeHoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stake_holders', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->text('address');
            $table->string('country');
            $table->string('province');
            $table->string('city');
            $table->string('postal_code');
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('card_number')->nullable(); # no rek
            $table->string('owner')->nullable();
            $table->string('bank')->nullable();
            $table->enum('type', ['supplier', 'customer', 'sales']);
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
        Schema::dropIfExists('stake_holders');
    }
}
