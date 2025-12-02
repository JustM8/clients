<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_stage_waiting_logs', function (Blueprint $table) {

            $table->id();

            // Прив'язка до проекту
            $table->foreignId('project_id')->constrained()->onDelete('cascade');

            // Прив'язка до активного етапу проекту (project_stages або project_stage_items)
            // Зараз робимо під ProjectStage, як ти і просив
            $table->unsignedBigInteger('stage_id')->nullable();

            // Хто запустив (адмін)
            $table->unsignedBigInteger('started_by_admin')->nullable();

            // Коментар адміна під час старту
            $table->text('admin_comment')->nullable();

            // Коли запущено
            $table->timestamp('started_at')->nullable();

            // Коли клієнт натиснув STOP (але це НЕ завершення)
            $table->timestamp('client_stopped_at')->nullable();

            // Коментар клієнта
            $table->text('client_comment')->nullable();

            // Час, коли менеджер натиснув "Відхилити"
            $table->timestamp('rejected_by_admin_at')->nullable();

            // Коментар адміна при відхиленні
            $table->text('rejected_admin_comment')->nullable();

            // Фінальна зупинка (коли менеджер у Telegram натиснув "Підтвердити")
            $table->timestamp('stopped_at')->nullable();

            // Хто підтвердив зупинку
            $table->unsignedBigInteger('stopped_by_admin')->nullable();

            // Загальна тривалість (секунди)
            $table->unsignedInteger('duration_seconds')->nullable();

            // Статус:
            // running — активний
            // client_stopped — клієнт натиснув STOP
            // completed — менеджер підтвердив
            $table->string('status')->default('running');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_stage_waiting_logs');
    }
};
