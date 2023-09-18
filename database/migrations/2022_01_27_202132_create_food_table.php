<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner')->default(0);
            $table->boolean('has_approved')->default(true);
            $table->string('name');

            $table->string('potion');

            $table->double('calories');
            $table->double('carbs');
            $table->double('proteins');
            $table->double('fat');

            $table->double('sat_fat');
            $table->double('fibers');

            $table->json('ingredients')->default('[]');

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
        Schema::dropIfExists('food');
    }
}
