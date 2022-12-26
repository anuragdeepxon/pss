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
        Schema::create('contract_invitations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('contract_id')->unsigned();
            $table->bigInteger('candidate_id')->unsigned();
            $table->boolean('is_candidate_accept')->default(0);
            $table->boolean('is_employer_accept')->default(0);
            $table->dateTime('candidate_accept_date_time')->default(null);
            $table->dateTime('employer_accept_date_time')->default(null);
            $table->tinyInteger('status');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('contract_id')->references('id')->on('contracts');
            $table->foreign('candidate_id')->references('id')->on('employers');
            $table->index('contract_id');
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
        Schema::dropIfExists('contract_invitations');
    }
};
