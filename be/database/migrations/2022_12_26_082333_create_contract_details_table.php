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
        Schema::create('contract_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('contract_id')->unsigned();
            $table->string('company_name')->nullable();
            $table->string('location')->nullable();
            $table->tinyInteger('role_id');
            $table->longText('experience')->nullable();
            $table->dateTime('from_date')->nullable();
            $table->dateTime('to_date')->nullable();
            $table->tinyInteger('shift_type')->default(1);
            $table->string('schedule')->nullable();
            $table->boolean('is_travel_allowance')->default(0);
            $table->boolean('is_meal_allowance')->default(0);
            $table->boolean('is_accommodation_allowance')->default(0);
            $table->float('travel_allowance_amount')->nullable();
            $table->float('meal_allowance_amount')->nullable();
            $table->float('accommodation_allowance_amount')->nullable();
            $table->tinyInteger('rate_type')->default(1);
            $table->float('rate_amount')->nullable();
            $table->longText('notes')->nullable();
            $table->dateTime('posting_start_date');
            $table->dateTime('posting_end_date');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('contract_id')->references('id')->on('contracts'); 
            $table->index('contract_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_details');
    }
};
