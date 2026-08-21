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
            $table->id();
            $table->string('title');
            $table->string('meta_keys')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_hot')->default(false);
            $table->boolean('is_new')->default(false);
            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->dateTime('sale_start_date')->nullable();
            $table->dateTime('sale_end_date')->nullable();
            $table->string('barcode')->nullable();
            $table->unsignedInteger('amount_in_stock')->default(0);
            $table->string('route')->unique();
            $table->integer('position')->default(0);
            $table->timestamps();
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
