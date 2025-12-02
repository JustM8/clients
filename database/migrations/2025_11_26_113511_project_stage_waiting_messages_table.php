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
        Schema::create('project_stage_waiting_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('waiting_log_id')->constrained('project_stage_waiting_logs')->cascadeOnDelete();

            $table->enum('from', ['client', 'admin']);
            $table->text('message')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
