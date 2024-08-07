<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResellerFundingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reseller_fundings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reseller_id')->nullable();
            $table->string('invoice')->nullable();
            $table->float('payable', 10, 2)->nullable();
            $table->float('payed', 10, 2)->nullable();
            $table->string('is_connect')->nullable();
            $table->string('status')->nullable();
            $table->date('month')->nullable();
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
        Schema::dropIfExists('reseller_fundings');
    }
}
