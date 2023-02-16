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
        Schema::create('users_meta', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user')->index('user_meta_index');
            $table->string('key')->index('meta_key_index');
            $table->longText('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_meta');
    }
};
