<?php

use App\Enums\ProductType;
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
            $table->enum('type', ProductType::cases())->default(ProductType::Goods->value);
            $table->string('name');

            $table->string('sku')->nullable(); 
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            $table->string('code')->nullable()->unique();
            $table->string('barcode')->nullable()->unique();

            $table->decimal('cost', 18, 2)->default(0);
            $table->decimal('price', 18, 2)->default(0);
            
            $table->boolean('manage_stock')->default(false);
            
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();

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