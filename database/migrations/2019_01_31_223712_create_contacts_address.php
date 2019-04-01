<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts_address', function (Blueprint $table) {
            $table->increments('contact_address_id');
            $table->integer('contact_id');
            $table->string('street');
            $table->string('city');
            $table->string('province');
            $table->string('country');
            $table->integer('zip_code');
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
        Schema::dropIfExists('contacts_address');
    }
}
