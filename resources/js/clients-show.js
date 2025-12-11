document.addEventListener("DOMContentLoaded", () => {

    const cfg = document.getElementById("timerConfig");
    if (!cfg) return;

    let freeSec = Number(cfg.dataset.freeSec);
    let paidSec = Number(cfg.dataset.paidSec);
    const rate = Number(cfg.dataset.rate);
    const currentStatus = cfg.dataset.status;

    const fetchUrl = cfg.dataset.fetchUrl;
    const stopUrl = cfg.dataset.stopUrl;

    const freeEl = document.getElementById('freeTimer');
    const paidEl = document.getElementById('paidTimer');
    const freeBox = document.getElementById('freeBox');
    const paidBox = document.getElementById('paidBox');

    function isWorkingTime() {
        const now = new Date();
        const day = now.getDay();
        if (day === 0 || day === 6) return false;

        const h = now.getHours();
        const m = now.getMinutes();
        if (h === 0 && m === 0) return false;

        return true;
    }

    function format(sec) {
        const h = String(Math.floor(sec / 3600)).padStart(2, '0');
        const m = String(Math.floor((sec % 3600) / 60)).padStart(2, '0');
        const s = String(sec % 60).padStart(2, '0');
        return `${h}:${m}:${s}`;
    }

    function updateVisibility() {
        if (freeSec > 0) {
            freeBox.style.display = 'block';
            paidBox.style.display = 'none';
        } else {
            freeBox.style.display = 'none';
            paidBox.style.display = 'block';
        }
    }

    updateVisibility();

    // Основний таймер
    setInterval(() => {
        if (!isWorkingTime()) return;

        if (freeSec > 0) {
            freeSec--;
            freeEl.textContent = format(freeSec);
        } else {
            paidSec++;
            paidEl.textContent = format(paidSec);

            if (rate > 0) {
                const amount = (paidSec / 3600) * rate;
                document.getElementById('paidAmount').textContent = '€' + amount.toFixed(2);
            }
        }

        updateVisibility();
    }, 1000);

    // Синхронізація раз на 30 сек
    setInterval(() => {
        fetch(fetchUrl)
            .then(r => r.json())
            .then(data => {
                if (data.status !== currentStatus) {
                    location.reload();
                }
            });
    }, 30000);

    // STOP button
    const stopBtn = document.getElementById('clientStopBtn');
    const commentField = document.getElementById('clientStopComment');

    stopBtn?.addEventListener("click", () => {
        let comment = commentField.value.trim();
        if (!comment) return alert("Напишіть коментар");

        stopBtn.disabled = true;

        fetch(stopUrl, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ comment })
        })
            .then(r => r.json())
            .then(d => {
                if (d.success) location.reload();
                else {
                    stopBtn.disabled = false;
                    alert(d.error ?? "Помилка");
                }
            })
            .catch(() => {
                stopBtn.disabled = false;
                alert("Помилка мережі");
            });
    });

});
