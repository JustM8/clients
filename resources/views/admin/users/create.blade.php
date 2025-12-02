@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.19/build/css/intlTelInput.min.css">
@endsection

@section('content')
    <div class="container mt-4">
        <h3>Створення користувача</h3>
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Ім’я</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3 position-relative">
                <label for="password" class="form-label">Пароль</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Пароль" required>
                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">👁</button>
                    <button type="button" class="btn btn-outline-primary" id="generatePassword">🔁</button>
                </div>
            </div>

            <div class="mb-3">
                <label>Компанія</label>
                <input type="text" name="company_name" class="form-control">
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Телефон</label>
                <input id="phone" type="tel" class="form-control" />
                <input id="phone_e164" name="phone" type="hidden" />
            </div>

            <button class="btn btn-success">Зберегти</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Назад</a>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.querySelector("#phone");
            const hidden = document.querySelector("#phone_e164");
            const utilsUrl = "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.19/build/js/utils.js";

            // Дочекайся завантаження utils.js перед ініціалізацією
            function initTelInput() {
                if (!window.intlTelInput || !input) return;

                const iti = window.intlTelInput(input, {
                    initialCountry: "ua",
                    preferredCountries: ["ua", "pl", "gb", "us"],
                    separateDialCode: true,
                    nationalMode: false,
                    formatOnDisplay: true,
                    autoPlaceholder: "polite",
                    utilsScript: utilsUrl // обов’язково тут
                });

                // Коли utils підвантажиться — примусове оновлення маски
                input.addEventListener("countrychange", () => {
                    setTimeout(() => {
                        const placeholder = input.getAttribute("placeholder");
                        console.log("Маска оновилась:", placeholder);
                    }, 500);
                });

                // Записуємо в hidden поле E.164 формат
                const updateHidden = () => {
                    hidden.value = iti.isValidNumber() ? iti.getNumber() : input.value;
                };
                input.addEventListener('blur', updateHidden);
                input.addEventListener('change', updateHidden);
            }

            // ⏳ Завантаження utils.js вручну, якщо ще не є в DOM
            if (!document.querySelector(`script[src="${utilsUrl}"]`)) {
                const script = document.createElement("script");
                script.src = utilsUrl;
                script.onload = initTelInput;
                document.body.appendChild(script);
            } else {
                initTelInput();
            }
            setTimeout(() => {
                const placeholder = input.placeholder || "";
                if (placeholder) {
                    const mask = placeholder.replace(/[0-9]/g, "9");
                    Inputmask({
                        mask: mask,
                        showMaskOnFocus: true,
                        showMaskOnHover: false,
                        clearIncomplete: true
                    }).mask(input);
                    console.log("Маска застосована:", mask);
                } else {
                    console.warn("Placeholder порожній, маску не застосовано");
                }
            }, 1000);
        });
        // 👁 toggle & генерація паролю
        const passwordInput = document.getElementById('password');
        const toggleBtn = document.getElementById('togglePassword');
        const generateBtn = document.getElementById('generatePassword');

        toggleBtn.addEventListener('click', () => {
            passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
            toggleBtn.textContent = passwordInput.type === 'password' ? '👁' : '🙈';
        });

        generateBtn.addEventListener('click', () => {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
            passwordInput.value = Array.from({ length: 12 }, () => chars[Math.floor(Math.random() * chars.length)]).join('');
        });
    </script>
@endsection

