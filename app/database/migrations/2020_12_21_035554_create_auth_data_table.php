<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_data', function (Blueprint $table) {
            $table->id();
            $table->longText('client_id');
            $table->longText('baseDomain');
            $table->longText('refreshToken');
            $table->longText('accessToken');
            $table->text('expires');
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
        Schema::dropIfExists('auth_data');
    }
}
