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
        Schema::create('branch_ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('sl_no',20)->nullable();
            $table->integer('branch_id')->unsigned();
            $table->foreign('branch_id')
                ->references('id')->on('branches')
                ->onDelete('cascade');
            $table->timestamp('transaction_date')->useCurrent();
            $table->string('transaction_code')->nullable();
            $table->integer('transaction_type_id');
            $table->integer('transaction_method_id');
            $table->double('amount',12,1)->default(0);
            $table->text('comments')->nullable();
            $table->integer('entry_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->enum('approve_status',['Submitted','Approved','Canceled','Updated'])->default('Submitted');

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
        Schema::dropIfExists('branch_ledgers');
    }
};
