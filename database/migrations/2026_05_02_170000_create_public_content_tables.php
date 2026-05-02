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
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('eyebrow')->nullable();
            $table->text('summary')->nullable();
            $table->json('content')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->boolean('is_published')->default(false)->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('media_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('collection')->default('general')->index();
            $table->string('alt_text')->nullable();
            $table->text('caption')->nullable();
            $table->string('file_path')->nullable();
            $table->string('disk')->default('public');
            $table->string('external_url')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->boolean('is_public')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->default('general')->index();
            $table->string('key')->unique();
            $table->string('label')->nullable();
            $table->text('value')->nullable();
            $table->string('type')->default('text');
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(true)->index();
            $table->timestamps();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->string('public_slug')->nullable()->unique();
            $table->text('public_summary')->nullable();
            $table->string('public_cover_path')->nullable();
            $table->boolean('is_public')->default(false)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->timestamp('public_published_at')->nullable()->index();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropUnique('projects_public_slug_unique');
            $table->dropColumn([
                'public_slug',
                'public_summary',
                'public_cover_path',
                'is_public',
                'is_featured',
                'public_published_at',
                'seo_title',
                'seo_description',
            ]);
        });

        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('media_items');
        Schema::dropIfExists('cms_pages');
    }
};
