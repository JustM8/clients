<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Наприклад: "Збір інформації", "Дизайн"
            $table->string('slug')->unique(); // "preparation", "design" і т.д.
            $table->unsignedInteger('order')->default(0); // порядок у списку
            $table->timestamps();
        });

        // ⚙️ Початкові статуси
        DB::table('project_statuses')->insert([
            ['name' => 'Збір інформації', 'slug' => 'preparation', 'order' => 1],
            ['name' => 'Дизайн', 'slug' => 'design', 'order' => 2],
            ['name' => 'Верстка', 'slug' => 'layout', 'order' => 3],
            ['name' => 'Натяжка', 'slug' => 'integration', 'order' => 4],
            ['name' => 'Тестування', 'slug' => 'testing', 'order' => 5],
            ['name' => 'Запуск', 'slug' => 'launch', 'order' => 6],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('project_statuses');
    }
};
