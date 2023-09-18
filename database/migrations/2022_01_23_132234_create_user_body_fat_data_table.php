<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBodyFatDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_body_fat_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->double('front_upper_arm');
            $table->double('back_upper_arm');
            $table->double('side_of_waist');
            $table->double('back_below_shoulder');
            $table->double('weight');
            $table->double('body_fat');
            $table->double('muscle_mass');
            $table->enum('body_fat_category', ['Lean','Good', 'Above Average']);
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
        Schema::dropIfExists('user_body_fat_data');
    }
}
