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
           
        Schema::create('contracts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid()->unique();
            $table->bigInteger('employer_id')->unsigned();
            $table->timestamps();
            $table->tinyInteger('status')->default(1);
            $table->softDeletes();
            $table->foreign('employer_id')->references('id')->on('employers'); 
            $table->index('employer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contracts');
    }
};
