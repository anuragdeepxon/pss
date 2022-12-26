<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_requirements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('candidate_id')->unsigned();
            $table->boolean('is_travel_allowance')->default(0);
            $table->boolean('is_meal_allowance')->default(0);
            $table->boolean('is_accommodation_allowance')->default(0);
            $table->float('travel_allowance_amount')->nullable();
            $table->float('meal_allowance_amount')->nullable();
            $table->float('accommodation_allowance_amount')->nullable();
            $table->tinyInteger('rate_type')->default(1);
            $table->float('rate_amount')->nullable();
            $table->longText('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('candidate_id')->references('id')->on('candidates'); 
            $table->index('candidate_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidate_requirements');
    }
};
