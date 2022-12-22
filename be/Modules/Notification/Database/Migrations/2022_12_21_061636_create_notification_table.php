<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('title');
                $table->longText('description');
                $table->bigInteger('send_by')->unsigned();
                $table->bigInteger('send_to')->unsigned();
                $table->text('send_by_model_type');
                $table->text('send_to_model_type');
                $table->boolean('is_read')->default(0);
                $table->boolean('type')->default(1);
                $table->boolean('status')->default(1);
                $table->softDeletes();
                $table->timestamps();
            });
        }
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
