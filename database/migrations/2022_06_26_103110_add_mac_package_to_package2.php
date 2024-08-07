<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMacPackageToPackage2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package2', function (Blueprint $table) {
            // $table->foreignId('tariffconfig_id')->default(0);
            // $table->foreignId('mac_package_id')->nullable();
            // $table->foreignId('server_id')->nullable();
            // $table->foreignId('protocol_id')->nullable();
            // $table->foreignId('m_profile_id')->nullable();
            // $table->string('rate')->nullable();
            // $table->string('selling_price')->nullable();
            // $table->string('validity_day')->nullable();
            // $table->string('minimum_activation_day')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('package2', function (Blueprint $table) {
            //
        });
    }
}
