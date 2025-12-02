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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // 🔹 Основна інформація
            $table->string('name');                       // Ім’я користувача або контактної особи
            $table->string('email')->unique();            // Email (для логіну + верифікації)
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');                   // Пароль

            // 🔹 Додаткові поля профілю
            $table->string('company_name')->nullable();   // Назва компанії
            $table->string('position')->nullable();       // Посада користувача (опціонально)
            $table->string('phone')->nullable();          // Телефон
            $table->string('telegram_id')->nullable();    // Для зв’язку через Telegram
            $table->text('notes')->nullable();            // Примітки або додаткова інформація

            // 🔹 Системні поля
            $table->enum('role', ['admin', 'client'])->default('client'); // Роль
            $table->rememberToken();                      // Для "запам’ятати мене"
            $table->timestamps();                         // created_at / updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
