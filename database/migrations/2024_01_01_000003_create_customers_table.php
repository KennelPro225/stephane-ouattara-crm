<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone', 30);
            $table->string('company')->nullable();
            $table->string('city')->nullable();
            $table->unsignedInteger('age')->nullable();
            $table->enum('source', ['website', 'referral', 'direct', 'social_media'])->default('website');
            $table->enum('status', ['lead', 'prospect', 'active', 'inactive'])->default('lead');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'source']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
