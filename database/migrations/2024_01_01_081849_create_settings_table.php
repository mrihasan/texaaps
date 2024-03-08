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
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('org_name');
            $table->integer('linked_user')->nullable();
            $table->string('org_slogan')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('contact_no1',25)->nullable();
            $table->string('contact_no2',25)->nullable();
            $table->string('email',100)->nullable();
            $table->string('web',100)->nullable();
            $table->string('vat_reg_no',50)->nullable();
            $table->string('language', 5)->default('en');
            $table->string('logo')->default('no-foto.png')->nullable();
            $table->longText('logo_base64')->nullable();
            $table->string('default_password')->default('Eis_777');
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
        Schema::dropIfExists('settings');
    }
};
