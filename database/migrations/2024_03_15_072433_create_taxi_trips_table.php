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
        Schema::create('taxi_trips', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('car_type');
            $table->string('driver_id');
            $table->string('car_id');
            $table->string('pickup');
            $table->string('destination');
            $table->string('distance');
            $table->string('price');
            $table->string('duration');
            $table->string('final_duration');
            $table->string('remaining');
            $table->string('status');
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
        Schema::dropIfExists('taxi_trips');
    }
};
