<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subcategories_navbar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_category_id')->constrained('categories_navbar')->onDelete('cascade');
            $table->string('display_name');
            $table->string('idpath');
            $table->string('path')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subcategories_navbar');
    }
};