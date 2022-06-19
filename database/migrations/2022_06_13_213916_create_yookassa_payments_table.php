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
        Schema::create('yookassa_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id');
            $table->bigInteger('shop_id');
            $table->string('recipient_account_id');
            $table->string('recipient_gateway_id');
            $table->string('payment_method_type')->nullable();
            $table->string('payment_method_title')->nullable();
            $table->decimal('amount');
            $table->string('currency');
            $table->decimal('income_amount')->nullable();
            $table->string('income_currency')->nullable();
            $table->string('confirmation_type');
            $table->string('return_url')->nullable();
            $table->string('confirmation_url')->nullable();
            $table->string('description');
            $table->string('external_id');
            $table->string('status');
            $table->boolean('paid');
            $table->boolean('refundable');
            $table->decimal('refunded_amount')->nullable();
            $table->string('refunded_currency')->nullable();
            $table->boolean('test');
            $table->timestamp('captured_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('receipt_registration')->nullable();
            $table->json('metadata')->nullable();
            $table->string('cancellation_party')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->text('error');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('yookassa_payments');
    }
};
