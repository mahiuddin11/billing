<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMacCustomerBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mac_customer_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->date('date_');
            $table->float('charge', 10, 2)->nullable();
            $table->foreignId('company_id');
            $table->foreignId('created_by')->nullable();
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
        Schema::dropIfExists('mac_customer_bills');
    }
}
