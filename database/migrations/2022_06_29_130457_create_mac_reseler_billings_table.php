<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMacReselerBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mac_reseler_billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mac_reseller_id');
            $table->date('billing_month')->nullable();
            $table->float('billing_amount', 10, 2)->nullable();
            $table->float('payed', 10, 2)->nullable();
            $table->float('due', 10, 2)->nullable();
            $table->foreignId('recive_by')->nullable();
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
        Schema::dropIfExists('mac_reseler_billings');
    }
}
