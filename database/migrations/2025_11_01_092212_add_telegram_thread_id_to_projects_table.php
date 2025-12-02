<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'telegram_thread_id')) {
                $table->string('telegram_thread_id')->nullable()->index()->after('status_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'telegram_thread_id')) {
                $table->dropColumn('telegram_thread_id');
            }
        });
    }
};
