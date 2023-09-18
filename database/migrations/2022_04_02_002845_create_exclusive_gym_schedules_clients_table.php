<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExclusiveGymSchedulesClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exclusive_gym_schedules_clients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('schedule_id');
            $table->unsignedBigInteger('client_id');
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
        Schema::dropIfExists('exclusive_gym_schedules_clients');
    }
}
