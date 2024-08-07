<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_requisitions', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('invoice_no')->nullable();
            $table->foreignId('account_id')->nullable();
            $table->foreignId('supplier_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->float('subtotal', 12, 2)->nullable();
            $table->float('discount', 12, 2)->nullable();
            $table->float('grand_total', 12, 2)->nullable();
            $table->integer('transportation')->nullable();
            $table->enum('status', ['Approved', 'Inactive', 'Pending', 'Cancel'])->default('Pending')->comment('default status set active , penidng status waiting for approbal');
            $table->integer('quantity')->nullable();
            $table->float('paid_amount', 12, 2)->nullable();
            $table->float('due_amount', 12, 2)->nullable();
            $table->longText('narration')->nullable();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();
            $table->index(['supplier_id']);
            $table->softDeletes();
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
        Schema::dropIfExists('purchase_requisitions');
    }
}
