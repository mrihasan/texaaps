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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('company_name_id')->nullable();
            $table->integer('product_type_id')->nullable();
            $table->integer('brand_id')->nullable();
            $table->integer('unit_id');
            $table->float('unitbuy_price',9,1);
            $table->float('unitsell_price',9,1);
            $table->integer('low_stock')->default(10);
            $table->text('description')->nullable();
            $table->enum('status',['Active','Inactive'])->default('Active');

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
        Schema::dropIfExists('products');
    }
};
