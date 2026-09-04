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
            $table->enum('audience', ['adolescents', 'adultes', 'entreprises']);
            $table->enum('type', ['individual', 'group', 'corporate']);
            $table->string('level')->nullable();
            $table->text('description');
            $table->decimal('price_amount', 10, 2)->nullable();
            $table->string('price_label');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('registration_deadline')->nullable();
            $table->string('duration_label');
            $table->string('ages_label');
            $table->unsignedInteger('max_participants');
            $table->string('image_path')->nullable();
            $table->boolean('featured')->default(false);
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programmes');
    }
};
