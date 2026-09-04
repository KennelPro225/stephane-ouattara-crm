<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programmes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->unsignedInteger('age_min')->default(10);
            $table->unsignedInteger('age_max')->default(60);
            $table->enum('type', ['individual', 'group', 'corporate', 'adolescents'])->default('group');
            $table->decimal('price', 10, 2)->default(0);
            $table->unsignedInteger('max_participants')->default(20);
            $table->date('start_date');
            $table->date('end_date');
            $table->date('registration_deadline')->nullable();
            $table->time('session_time')->nullable();
            $table->integer('duration_hours')->default(2);
            $table->string('location')->default('Abidjan, Côte d\'Ivoire');
            $table->string('image_path')->nullable();
            $table->boolean('featured')->default(false);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'start_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programmes');
    }
};
