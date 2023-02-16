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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('creator_id')->index();
            $table->string('entity', 255)->index();
            $table->bigInteger('entity_id')->index();
            $table->mediumText('title');
            $table->longText('description');
            $table->boolean('repeat')->default(false);
            $table->json('repeat_settings')->nullable();
            $table->timestamp('started_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
};
