<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionInvoiceDiscountsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('subscription_invoice_discounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscription_invoice_id');
            $table->unsignedBigInteger('subscription_id');
            $table->string('discount_type');
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->decimal('value', 20, 2);
            $table->string('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('subscription_invoice_discounts');
    }

}
