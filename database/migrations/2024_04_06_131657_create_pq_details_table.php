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
        Schema::create('pq_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('price_quotation_id')->unsigned();
            $table->foreign('price_quotation_id')
                ->references('id')->on('price_quotations')
                ->onDelete('cascade');
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('cascade');
            $table->integer('brand_id')->nullable();
            $table->string('model')->nullable();
            $table->text('product_details')->nullable();
            $table->integer('qty');
            $table->string('unit_name');
            $table->float('unit_price',12,1)->nullable();
            $table->float('line_total',12,1);

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
        Schema::dropIfExists('pq_details');
    }
};
