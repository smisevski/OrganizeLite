<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('contact_id');
            $table->string('contact_firstname', 30);
            $table->string('contact_lastname', 30);
            $table->string('contact_gender', 6);
            $table->date('contact_birthdate');
            $table->string('contact_idcard', 30);
            $table->string('contact_phone', 30);
            $table->string('contact_mobile', 30);
            $table->string('contact_fax', 30);
            $table->string('contact_status', 30);
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
        Schema::dropIfExists('contacts');
    }
}
