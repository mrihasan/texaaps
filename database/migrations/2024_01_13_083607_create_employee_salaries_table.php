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
        Schema::create('employee_salaries', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('branch_id')->unsigned();
            $table->tinyInteger('salary_month');
            $table->integer('year');
            $table->enum('type', ['Salary Payslip', 'Bonus Payslip', 'Payment']);
            $table->integer('salary_amount')->nullable();
            $table->integer('working_day')->nullable();
            $table->integer('holiday_weekend')->nullable();
            $table->integer('leave_day')->nullable();
            $table->integer('absent_day')->nullable();
            $table->integer('paidsalary_amount');
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
        Schema::dropIfExists('employee_salaries');
    }
};
