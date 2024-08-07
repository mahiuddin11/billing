<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultipleToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->longText('create_msg')->nullable();
            $table->longText('billing_exp_msg')->nullable();
            $table->longText('bill_paid_msg')->nullable();
            $table->longText('bill_exp_warning_msg')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->longText('create_msg')->nullable();
            $table->longText('billing_exp_msg')->nullable();
            $table->longText('bill_paid_msg')->nullable();
            $table->longText('bill_exp_warning_msg')->nullable();
        });
    }
}
