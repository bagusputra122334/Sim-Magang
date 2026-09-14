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
        Schema::create('landing_contents', function (Blueprint $table) {
            $table->id();
            $table->string('section'); // 'about', 'advantage', 'workflow', 'faq'
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // For SVG strings, icon classes, or image paths
            $table->integer('order')->default(0); // To sort items
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['section', 'is_active', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_contents');
    }
};
