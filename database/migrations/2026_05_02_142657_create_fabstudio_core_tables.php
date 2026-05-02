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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('individual')->index();
            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable();
            $table->string('identification')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('status')->default('active')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable();
            $table->string('source')->nullable()->index();
            $table->string('status')->default('new')->index();
            $table->string('interest')->nullable();
            $table->text('message')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('converted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code')->nullable()->unique();
            $table->string('name');
            $table->string('typology')->nullable()->index();
            $table->string('status')->default('planning')->index();
            $table->string('current_phase')->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->decimal('budget_estimate', 14, 2)->nullable();
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_phases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('status')->default('pending')->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->date('starts_at')->nullable();
            $table->date('due_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_phase_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('status')->default('pending')->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->date('due_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('project_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('uploaded_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('category')->default('general')->index();
            $table->string('visibility')->default('internal')->index();
            $table->string('status')->default('draft')->index();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('document_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_document_id')->constrained()->cascadeOnDelete();
            $table->foreignId('uploaded_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('version_number');
            $table->string('original_name');
            $table->string('file_path');
            $table->string('disk')->default('local');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('checksum')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_current')->default(true)->index();
            $table->timestamps();

            $table->unique(['project_document_id', 'version_number']);
        });

        Schema::create('visual_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('uploaded_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('type')->default('image')->index();
            $table->string('visibility')->default('internal')->index();
            $table->string('status')->default('draft')->index();
            $table->string('file_path')->nullable();
            $table->string('preview_path')->nullable();
            $table->string('external_url')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->json('metadata')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('quote_number')->nullable()->unique();
            $table->string('title');
            $table->string('status')->default('draft')->index();
            $table->string('currency', 3)->default('COP');
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('tax', 14, 2)->default(0);
            $table->decimal('discount', 14, 2)->default(0);
            $table->decimal('total', 14, 2)->default(0);
            $table->date('valid_until')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('quote_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('version_number');
            $table->string('status')->default('draft')->index();
            $table->json('content')->nullable();
            $table->string('ai_model')->nullable();
            $table->string('ai_prompt_hash')->nullable();
            $table->string('pdf_path')->nullable();
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('tax', 14, 2)->default(0);
            $table->decimal('discount', 14, 2)->default(0);
            $table->decimal('total', 14, 2)->default(0);
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->unique(['quote_id', 'version_number']);
        });

        Schema::create('project_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->nullableMorphs('commentable');
            $table->string('type')->default('comment')->index();
            $table->string('visibility')->default('internal')->index();
            $table->text('body');
            $table->string('decision')->nullable()->index();
            $table->timestamp('decided_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_comments');
        Schema::dropIfExists('quote_versions');
        Schema::dropIfExists('quotes');
        Schema::dropIfExists('visual_assets');
        Schema::dropIfExists('document_versions');
        Schema::dropIfExists('project_documents');
        Schema::dropIfExists('milestones');
        Schema::dropIfExists('project_phases');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('clients');
    }
};
