<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories_home', function (Blueprint $table) {
            $table->id();
            $table->string('display_name');
            $table->string('idpath')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('custom_html')->nullable();
            $table->string('path')->nullable();
            $table->integer('order')->default(0);
            $table->integer('max_articles')->default(2);
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories_home');
    }
};