import Inputmask from "inputmask";
import intlTelInput from "intl-tel-input";
import "intl-tel-input/build/css/intlTelInput.min.css";
import flagsUrl from "intl-tel-input/build/img/flags.png";
import flags2xUrl from "intl-tel-input/build/img/flags@2x.png";

document.addEventListener("DOMContentLoaded", () => {
    const input = document.querySelector("#phone");
    const hidden = document.querySelector("#phone_e164");
    if (!input) return;

    // 🏁 прапорці
    const style = document.createElement("style");
    style.innerHTML = `
        .iti__flag {background-image: url(${flagsUrl});}
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .iti__flag {background-image: url(${flags2xUrl});}
        }
    `;
    document.head.appendChild(style);

    const utilsUrl = "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.19/build/js/utils.js";

    // === ІНІЦІАЛІЗАЦІЯ З ЧІТКОЮ ЧЕРГОВІСТЮ ===
    const initTelInput = () => {
        if (!window.intlTelInput) {
            console.warn("intlTelInput ще не готовий, повтор через 150 мс...");
            return setTimeout(initTelInput, 150);
        }

        const iti = window.intlTelInput(input, {
            initialCountry: "ua",
            preferredCountries: ["ua", "pl", "gb", "us"],
            separateDialCode: true,
            nationalMode: false,
            formatOnDisplay: true,
            autoPlaceholder: "polite",
            utilsScript: utilsUrl,
        });

        // === Коли зʼявиться placeholder — ставимо маску ===
        const applyMask = () => {
            const placeholder = input.placeholder;
            if (!placeholder || !/\d/.test(placeholder)) {
                console.warn("⏳ Placeholder ще не готовий, повтор через 200 мс...");
                return setTimeout(applyMask, 200);
            }

            const mask = placeholder.replace(/[0-9]/g, "9");
            Inputmask({
                mask: mask,
                showMaskOnFocus: true,
                showMaskOnHover: false,
                clearIncomplete: true,
            }).mask(input);
            console.log("✅ Маска застосована:", mask);
        };
        applyMask();

        input.addEventListener("countrychange", () => setTimeout(applyMask, 400));

        // === Оновлення hidden у форматі E.164 ===
        const updateHidden = () => {
            hidden.value = iti.isValidNumber() ? iti.getNumber() : input.value;
        };
        input.addEventListener("blur", updateHidden);
        input.addEventListener("change", updateHidden);
    };

    // === Завантажуємо utils.js перед запуском ===
    const script = document.createElement("script");
    script.src = utilsUrl;
    script.onload = initTelInput;
    document.body.appendChild(script);
});

// ==============================
// 🔐 Пароль — toggle + генерація
// ==============================
document.addEventListener("DOMContentLoaded", () => {
    const passwordInput = document.getElementById("password");
    const toggleBtn = document.getElementById("togglePassword");
    const generateBtn = document.getElementById("generatePassword");

    if (toggleBtn && passwordInput) {
        toggleBtn.addEventListener("click", () => {
            const isHidden = passwordInput.type === "password";
            passwordInput.type = isHidden ? "text" : "password";
            toggleBtn.textContent = isHidden ? "🙈" : "👁";
        });
    }

    if (generateBtn && passwordInput) {
        generateBtn.addEventListener("click", () => {
            const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*";
            const newPassword = Array.from({ length: 12 }, () =>
                chars[Math.floor(Math.random() * chars.length)]
            ).join("");
            passwordInput.value = newPassword;
            console.log("🔑 Згенеровано пароль:", newPassword);
        });
    }
});
