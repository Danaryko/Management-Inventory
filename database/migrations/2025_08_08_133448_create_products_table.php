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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // product_id
            $table->string('name');
            $table->unsignedBigInteger('category_id');
            $table->text('description')->nullable();
            $table->string('sku')->unique();
            $table->string('brand')->nullable();
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock_quantity')->default(0);
            $table->integer('min_stock_level')->default(0);
            $table->string('image')->nullable();
            $table->timestamps();
            // Foreign key constraint
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
