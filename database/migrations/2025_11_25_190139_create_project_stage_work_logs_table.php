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
        Schema::create('project_stage_work_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('stage_item_id')->constrained('project_stage_items')->onDelete('cascade');

            $table->dateTime('started_at');
            $table->dateTime('stopped_at')->nullable();

            $table->integer('duration_seconds')->default(0);

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
        Schema::dropIfExists('project_stage_work_logs');
    }
};
