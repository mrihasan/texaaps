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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->BigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamp('joining_date')->useCurrent();
            $table->date('date_of_birth')->nullable()->default(null);
            $table->string('nid')->nullable();
//            $table->string('cell_no')->nullable();
            $table->string('contact_no1')->nullable();
            $table->string('contact_no2')->nullable();
            $table->enum('gender',['Male','Female','Others'])->nullable()->default('Male');
            $table->text('address')->nullable();
            $table->integer('company_name_id')->nullable();

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
        Schema::dropIfExists('profiles');
    }
};
