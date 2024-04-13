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
        Schema::create('ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('sl_no',20)->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->integer('branch_id')->unsigned();
            $table->timestamp('transaction_date')->useCurrent();
            $table->string('transaction_code');
            $table->integer('transaction_type_id');
            $table->float('amount',12,1)->default(0);
            $table->integer('transaction_method_id');
            $table->text('comments')->nullable();
            $table->integer('entry_by');
            $table->integer('updated_by');
            $table->bigInteger('invoice_id')->nullable();
            $table->enum('approve_status',['Submitted','Approved','Canceled','Updated']);
            $table->integer('checked_by')->nullable();
            $table->timestamp('checked_date')->nullable();
            $table->integer('approved_by')->nullable();
            $table->timestamp('approved_date')->nullable();
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
        Schema::dropIfExists('ledgers');
    }
};
