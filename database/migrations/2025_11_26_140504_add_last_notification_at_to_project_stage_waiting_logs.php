<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_stage_waiting_logs', function (Blueprint $table) {
            $table->timestamp('last_notification_at')->nullable()->after('client_stopped_at');
        });
    }

    public function down()
    {
        Schema::table('project_stage_waiting_logs', function (Blueprint $table) {
            $table->dropColumn('last_notification_at');
        });
    }

};
