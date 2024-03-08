<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('branch_id');
            $table->float('salary_amount',10,1);
            $table->float('bonus_amount',10,1);
            $table->string('id_number',20)->nullable();
            $table->string('designation')->nullable();
//            $table->date('joining_day')->useCurrent();
            $table->date('last_working_day')->nullable();
            $table->enum('religion',['Islam','Hinduism','Christianity','Others'])->default('Islam');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
