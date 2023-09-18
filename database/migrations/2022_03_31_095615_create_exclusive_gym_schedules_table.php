<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExclusiveGymSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exclusive_gym_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gym_id');
            $table->unsignedBigInteger('trainer_id');
            $table->json('client_ids')->default('[]');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->boolean('confirmed')->default(false);
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
        Schema::dropIfExists('exclusive_gym_schedules');
    }
}
