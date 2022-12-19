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
        Schema::create('forget_password_otps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('otp');
            $table->dateTime('otp_expire_date_time');
            $table->string('model_type');
            $table->bigInteger('model_id');
            $table->index('model_id');
            $table->index('model_type');
            $table->boolean('is_verify')->default(0);
            $table->dateTime('verify_at')->nullable();
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
        Schema::dropIfExists('forget_password_otps');
    }
};
