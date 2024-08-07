<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->string('queue_mid')->nullable();
            $table->string('queue_name')->nullable();
            $table->string('queue_target')->nullable();
            $table->foreignId('server_id')->nullable();
            $table->string('queue_dst')->nullable();
            $table->string('queue_max_upload')->nullable();
            $table->string('queue_max_download')->nullable();
            $table->string('queue_disabled')->nullable();
            $table->float('amount', 10, 2)->nullable();
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
        Schema::dropIfExists('queues');
    }
}
