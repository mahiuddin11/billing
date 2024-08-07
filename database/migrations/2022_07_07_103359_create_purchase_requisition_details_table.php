<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseRequisitionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_requisition_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_requisition_id');
            $table->foreignId('product_category_id');
            $table->foreignId('product_id');
            $table->integer('quantity')->nullable();
            $table->float('unit_price', 12, 2)->nullable();
            $table->float('total_price', 12, 2)->nullable();
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
        Schema::dropIfExists('purchase_requisition_details');
    }
}
