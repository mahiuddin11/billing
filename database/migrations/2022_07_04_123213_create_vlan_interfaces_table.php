<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVlanInterfacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vlan_interfaces', function (Blueprint $table) {
            $table->id();
            $table->string('mid')->nullable();
            $table->string('name')->nullable();
            $table->foreignId('server_id')->nullable();
            $table->string('type')->nullable();
            $table->string('mtu')->nullable();
            $table->string('actual_mtu')->nullable();
            $table->string('running')->nullable();
            $table->string('disabled')->nullable();
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
        Schema::dropIfExists('vlan_interfaces');
    }
}
