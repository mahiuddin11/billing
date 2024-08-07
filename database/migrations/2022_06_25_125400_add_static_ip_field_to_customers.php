<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStaticIpFieldToCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            // $table->string('queue_name')->nullable();
            // $table->string('queue_target')->nullable();
            // $table->string('queue_dst')->nullable();
            // $table->string('queue_max_upload')->nullable();
            // $table->string('queue_max_download')->nullable();
            // $table->string('queue_disabled')->nullable();
            // $table->string('queue_mid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
}
