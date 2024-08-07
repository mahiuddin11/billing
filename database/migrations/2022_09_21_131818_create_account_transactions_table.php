<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice');
            $table->foreignId('table_id');
            $table->foreignId('company_id');
            $table->foreignId('account_id');
            $table->integer('type')->comment('1 => income, 2 => expense , 3 => salary , 4 => Internet bill, 5 => reseller , 6 => upstream, macresellerbill => 7,balancetransfer => 8');
            $table->float('debit', 10, 2)->nullable();
            $table->float('credit', 10, 2)->nullable();
            $table->longText('remark')->nullable();
            $table->foreignId('supplier_id')->nullable();
            $table->foreignId('customer_id')->nullable();
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
        Schema::dropIfExists('account_transactions');
    }
}
