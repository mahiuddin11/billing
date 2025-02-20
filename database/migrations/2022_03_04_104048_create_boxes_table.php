<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->nullable();
            $table->foreignId('subzone_id')->nullable();
            $table->foreignId('tj_id')->nullable();
            $table->foreignId('splitter_id')->nullable();
            $table->foreignId('box_id')->nullable();
            $table->string('name');
            $table->integer('core')->nullable()->comment('jotogola connection add kora jabe');
            $table->integer('connected')->nullable()->comment('jotogola connected hoia gece');
            $table->integer('remain')->nullable()->comment('joto gola connection baki ace');
            $table->foreignId('create_by')->nullable();
            $table->foreignId('update_by')->nullable();
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
        Schema::dropIfExists('boxes');
    }
}
