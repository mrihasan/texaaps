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
        Schema::create('company_names', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',190);
            $table->string('code_name',20)->nullable();
            $table->string('address')->nullable();
            $table->string('address2')->nullable();
            $table->string('contact_no',20)->nullable();
            $table->string('contact_no2',20)->nullable();
            $table->string('email',50)->nullable();
            $table->string('web',50)->nullable();
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
        Schema::dropIfExists('company_names');
    }
};
