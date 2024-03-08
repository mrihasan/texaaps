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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->integer('branch_id')->unsigned();
            $table->integer('expense_type_id')->unsigned();
            $table->foreign('expense_type_id')
                ->references('id')->on('expense_types')
                ->onDelete('cascade');
            $table->timestamp('expense_date')->useCurrent();
            $table->float('expense_amount',10,2);
            $table->enum('status',['Submitted','Approved','Canceled'.'Updated']);
            $table->string('comments')->nullable();
            $table->integer('user_id');
            $table->string('transaction_code',30)->nullable();
            $table->integer('transaction_method_id');
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
        Schema::dropIfExists('expenses');
    }
};
