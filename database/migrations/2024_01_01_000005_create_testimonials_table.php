<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('programme_id')->nullable()->constrained()->nullOnDelete();
            $table->string('author_name');
            $table->string('author_title')->nullable();
            $table->string('author_company')->nullable();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('message');
            $table->string('image_path')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('approved')->default(true);
            $table->timestamps();

            $table->index(['approved', 'featured']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
