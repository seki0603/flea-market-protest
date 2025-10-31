<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->unsignedInteger('price');
            $table->enum('payment_method', ['コンビニ支払い', 'カード支払い']);
            $table->enum('payment_status', ['pending', 'paid', 'failed']);
            $table->char('ship_postal_code', 8);
            $table->string('ship_address');
            $table->string('ship_building')->nullable();
            $table->enum('status', ['取引中', '取引完了'])->default('取引中');
            $table->timestamp('ordered_at');
            $table->timestamp('paid_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
