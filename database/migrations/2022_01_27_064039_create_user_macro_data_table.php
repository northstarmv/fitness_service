<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMacroDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_macro_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->unsignedBigInteger('trainer_id');

            $table->enum('fit_category', ['Loss', 'Gain','OVERRIDE']);
            $table->enum('fit_program', ['Moderate', 'Intense', 'HighActive','OVERRIDE']);
            $table->enum('fit_mode', ['Controlled', 'Accelerated', 'SuperAccelerated','CalBoost', 'ProteinBoost','OVERRIDE']);

            $table->double('lean_body_mass');
            $table->double('active_calories');
            $table->double('target_calories');

            $table->double('target_carbs');
            $table->double('target_protein');
            $table->double('target_fat');

            $table->boolean('override')->default(false);

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
        Schema::dropIfExists('user_macro_data');
    }
}
