<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create orders table
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->timestamps();
        });

        // Create pivot table for order_product (many-to-many relation)
        Schema::create('order_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Foreign key to orders
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Foreign key to products
            $table->integer('quantity')->default(1); // Optional: Add quantity field to track product quantity per order
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_product'); // Drop the pivot table first
        Schema::dropIfExists('orders');        // Drop the orders table
    }
};
