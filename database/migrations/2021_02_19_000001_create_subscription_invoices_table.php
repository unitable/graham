<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionInvoicesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('subscription_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->unsignedBigInteger('subscription_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('plan_price_id');
            $table->string('method');
            $table->unsignedBigInteger('method_id')->nullable();
            $table->string('engine');
            $table->string('currency_code', 3);
            $table->decimal('currency_rate', 12, 10);
            $table->decimal('subtotal', 20, 2);
            $table->decimal('total', 20, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('subscription_invoices');
    }

}
