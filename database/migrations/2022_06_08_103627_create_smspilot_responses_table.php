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
        Schema::create('smspilot_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sms_request_id');
            $table->bigInteger('server_id');
            $table->string('phone');
            $table->decimal('price');
            $table->integer('status');
            $table->decimal('balance');
            $table->decimal('cost');
            $table->bigInteger('server_packet_id');
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
        Schema::dropIfExists('smspilot_responses');
    }
};
