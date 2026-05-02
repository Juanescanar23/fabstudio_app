<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quote_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('type')->default('design');
            $table->string('status')->default('active')->index();
            $table->string('currency', 3)->default('COP');
            $table->unsignedInteger('default_valid_days')->default(30);
            $table->text('description')->nullable();
            $table->json('sections')->nullable();
            $table->json('line_items')->nullable();
            $table->text('terms')->nullable();
            $table->text('ai_instructions')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quote_templates');
    }
};
