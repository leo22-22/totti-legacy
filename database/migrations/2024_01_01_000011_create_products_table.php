<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('team')->nullable();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->string('sku')->unique()->nullable();
            $table->json('images')->nullable();
            $table->string('main_image')->nullable();
            $table->json('sizes')->nullable();
            $table->json('colors')->nullable();
            $table->json('composition')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new')->default(true);
            $table->integer('stock')->default(0);
            $table->integer('views')->default(0);
            $table->string('gender')->default('unisex');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
