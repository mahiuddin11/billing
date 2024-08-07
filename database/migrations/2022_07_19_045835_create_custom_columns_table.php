<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomColumnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_columns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_details_id');
            $table->foreignId('product_id')->nullable();
            $table->string('columns_one')->nullable();
            $table->string('columns_two')->nullable();
            $table->string('columns_three')->nullable();
            $table->string('columns_four')->nullable();
            $table->string('columns_five')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('custom_columns');
    }
}
