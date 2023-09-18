<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDailyFoodDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_daily_food_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            $table->json('food_data');

            $table->double('calories');

            $table->double('carbs');
            $table->double('protein');
            $table->double('fat');

            $table->json('macro_profile');
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
        Schema::dropIfExists('user_daily_food_data');
    }
}
