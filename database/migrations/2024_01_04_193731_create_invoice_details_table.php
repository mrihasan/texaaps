<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('invoice_id')->unsigned();
            $table->foreign('invoice_id')
                ->references('id')->on('invoices')
                ->onDelete('cascade');
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('cascade');
            $table->integer('branch_id')->unsigned();
            $table->enum('transaction_type', ['Sales', 'Purchase','Order','Return','Put Back']);
            $table->integer('qty');
            $table->string('unit_name');
//            $table->float('mrpUnitPrice',8,1);
//            $table->float('discountPercentage',8,1);
//            $table->float('discountUnit',6,1);
//            $table->float('discountedMrp',6,1);
//            $table->float('mrpTotal',9,1);
//            $table->float('discountTotal',8,1);
//            $table->float('discountedTotalMrp',9,1);
            $table->float('ubuy_price',8,1)->nullable();
            $table->float('usell_price',8,1)->nullable();
            $table->float('line_total',9,1);
            $table->tinyInteger('status')->nullable();

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
        Schema::dropIfExists('invoice_details');
    }
};
