<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWorkoutPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_workout_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('trainer_id');
            $table->string('user_name');
            $table->string('user_email');

            $table->string('title');
            $table->string('description',512);

            $table->json('workout_plan');
            $table->integer('steps');
            $table->integer('completed_steps')->default(0);
            $table->boolean('completed')->default(false);
            $table->boolean('finished')->default(false);
            $table->json('feedback')->nullable();
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
        Schema::dropIfExists('user_workout_plans');
    }
}
