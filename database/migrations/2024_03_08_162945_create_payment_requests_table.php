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
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->string('tracking_code');
            $table->string('req_no');
            $table->timestamp('req_date');

            $table->integer('branch_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->string('model')->nullable();
            $table->string('workorder_refno')->nullable();
            $table->date('workorder_date')->nullable();
            $table->float('workorder_amount', 10, 1)->nullable();

            $table->integer('supplier_id')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_no')->nullable();
            $table->float('amount', 10, 1);

            $table->string('account_name')->nullable();
            $table->string('account_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->integer('transaction_method_id')->nullable();
            $table->string('expected_bill')->nullable();
            $table->integer('expected_day')->nullable();

//            $table->integer('prepared_by')->nullable();
            $table->integer('checked_by')->nullable();
            $table->integer('approved_by')->nullable();
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
        Schema::dropIfExists('payment_requests');
    }
};
