<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddResellerFundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('add_reseller_funds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reseller_id');
            $table->foreignId('recive_by')->nullable();
            $table->foreignId('create_by')->nullable();
            $table->date('date')->nullable();
            $table->float('fund', 10, 2)->nullable();
            $table->float('payed', 10, 2)->nullable();
            $table->float('due', 10, 2)->nullable();
            $table->longText('note', 10, 2)->nullable();
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
        Schema::dropIfExists('add_reseller_funds');
    }
}
