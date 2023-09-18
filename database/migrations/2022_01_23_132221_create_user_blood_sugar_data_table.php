<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBloodSugarDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_blood_sugar_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->double('fasting_blood_sugar');
            $table->double('random_blood_sugar');
            $table->enum('fbs_category',['Low','Normal','PreDiabetes','Diabetes']);
            $table->enum('rbs_category',['Low','Normal','PreDiabetes','Diabetes']);
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
        Schema::dropIfExists('user_blood_sugar_data');
    }
}
