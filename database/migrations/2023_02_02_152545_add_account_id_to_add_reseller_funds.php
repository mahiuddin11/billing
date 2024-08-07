<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountIdToAddResellerFunds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('add_reseller_funds', function (Blueprint $table) {
            $table->foreignId('payment_id')->nullable()->after('create_by');
            $table->foreignId('account_id')->nullable()->after('create_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('add_reseller_funds', function (Blueprint $table) {
            //
        });
    }
}
