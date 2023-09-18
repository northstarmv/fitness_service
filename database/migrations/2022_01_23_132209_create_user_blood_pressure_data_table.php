<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBloodPressureDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_blood_pressure_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            $table->string('systolic');
            $table->string('diastolic');

            $table->enum('blood_pressure_category',[
                'Low',
                'Normal',
                'Elevated',
                'Hypertension Stage I',
                'Hypertension Stage II',
                'Hypertension Crisis',
            ]);

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
        Schema::dropIfExists('user_blood_pressure_data');
    }
}
