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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code');
            $table->string('sl_no',20)->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->integer('branch_id')->unsigned();
            $table->timestamp('transaction_date')->useCurrent();
            $table->string('reference')->nullable();
            $table->enum('transaction_type', ['Sales', 'Purchase','Order','Return','Put Back']);
            $table->float('product_total', 12, 1)->nullable(); //invoice sub_total
            $table->float('vat_per', 8, 1)->nullable();       //invoice vat %
            $table->float('vat', 8, 1)->nullable();           //invoice vat amount
            $table->float('disc_per', 8, 1)->nullable();      //invoice discount %
            $table->float('discount', 12, 1)->nullable();      //invoice  discount amount
            $table->float('total_amount', 12, 1); //invoice total before less amount
            $table->float('less_amount', 12,1)->nullable();   //invoice less amount
            $table->float('invoice_total', 12, 1); //invoice total
            $table->integer('entry_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->text('notes')->nullable();
            $table->string('attachment')->nullable();

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
        Schema::dropIfExists('invoices');
    }
};
