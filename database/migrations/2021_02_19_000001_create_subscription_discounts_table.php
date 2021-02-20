<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionDiscountsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('subscription_discounts', function (Blueprint $table) {
            $table->id();
            $table->string('discount_type');
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->decimal('value', 20, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('subscription_discounts');
    }

}
