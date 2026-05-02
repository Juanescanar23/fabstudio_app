<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->timestamp('exported_at')->nullable()->after('approved_at');
        });

        Schema::table('quote_versions', function (Blueprint $table) {
            $table->foreignId('quote_template_id')->nullable()->after('quote_id');
            $table->foreignId('reviewed_by_id')->nullable()->after('created_by_id');
            $table->foreignId('approved_by_id')->nullable()->after('reviewed_by_id');
            $table->string('pdf_disk')->default('local')->after('pdf_path');
            $table->timestamp('exported_at')->nullable()->after('approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('quote_versions', function (Blueprint $table) {
            $table->dropColumn([
                'quote_template_id',
                'reviewed_by_id',
                'approved_by_id',
                'pdf_disk',
                'exported_at',
            ]);
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn('exported_at');
        });
    }
};
