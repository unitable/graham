<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionInvoiceFlagsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('subscription_invoice_flags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscription_invoice_id');
            $table->unsignedBigInteger('subscription_id');
            $table->string('type');
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->text('data')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('subscription_invoice_flags');
    }

}
