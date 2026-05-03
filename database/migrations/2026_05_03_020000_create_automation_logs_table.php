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
        Schema::create('automation_logs', function (Blueprint $table) {
            $table->id();
            $table->string('automation_key')->index();
            $table->string('category')->default('operation')->index();
            $table->string('severity')->default('info')->index();
            $table->string('status')->default('pending')->index();
            $table->nullableMorphs('subject');
            $table->nullableMorphs('recipient');
            $table->string('recipient_email')->nullable()->index();
            $table->string('channel')->default('mail')->index();
            $table->string('title');
            $table->text('summary')->nullable();
            $table->json('payload')->nullable();
            $table->string('deduplication_key')->unique();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('automation_logs');
    }
};
