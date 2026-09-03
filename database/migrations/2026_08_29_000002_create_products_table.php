<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('code')->nullable()->index();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('brand')->nullable()->index();
            $table->string('subcategory')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedInteger('price')->nullable()->index();
            $table->char('currency', 3)->default('COP');
            $table->string('presentation')->nullable();
            $table->string('net_content')->nullable();
            $table->unsignedInteger('units')->nullable();
            $table->longText('ingredients')->nullable();
            $table->longText('usage')->nullable();
            $table->longText('precautions')->nullable();
            $table->json('catalog_benefits')->nullable();
            $table->string('image')->nullable();
            $table->json('images')->nullable();
            $table->unsignedSmallInteger('source_page');
            $table->enum('availability', ['available', 'confirm', 'coming_soon', 'unknown'])->default('confirm');
            $table->boolean('featured')->default(false);
            $table->timestamps();

            $table->index(['category_id', 'availability']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
