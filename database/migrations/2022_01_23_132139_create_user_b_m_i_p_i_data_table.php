<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBMIPIDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_b_m_i_p_i_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->double('weight');
            $table->double('height');
            $table->double('bmi');
            $table->double('pi');

            $table->enum('bmi_category',['Underweight','Normal','Overweight','Obesity']);
            $table->enum('pi_category',['Underweight','Normal','Overweight','Obesity']);
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
        Schema::dropIfExists('user_b_m_i_p_i_data');
    }
}
