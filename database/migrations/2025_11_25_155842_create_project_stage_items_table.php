<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_stage_items', function (Blueprint $table) {
            $table->id();

            // Зв’язки
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('stage_id')->constrained('project_stages')->onDelete('cascade');

            // Дати та час
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('expected_end_date')->nullable();

            // Накопичений час
            $table->unsignedInteger('spent_seconds')->default(0);

            // Позиція етапу в проекті
            $table->unsignedInteger('position')->default(1);
            $table->boolean('custom')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_stage_items');
    }
};
