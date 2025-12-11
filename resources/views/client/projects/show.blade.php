@extends('layouts.app')

@section('content')
    <style>
        .project-container {
            background: rgba(42, 42, 42, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 72, 0, 0.2);
            border-radius: 16px;
            padding: 2rem;
            margin-top: 2rem;
        }

        h3, h4 {
            color: rgba(255, 255, 255, 0.95);
        }

        h3 {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #ff4800 0%, #ff6b00 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
        }

        h4 {
            color: #ff4800;
            font-size: 1.3rem;
            font-weight: 600;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        strong {
            color: #ff4800;
        }

        p {
            color: rgba(255, 255, 255, 0.8);
        }

        .card {
            background: rgba(13, 13, 13, 0.4);
            border: 1px solid rgba(255, 72, 0, 0.2);
            border-radius: 12px;
            color: rgba(255, 255, 255, 0.9);
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.2);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #22c55e;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.2);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #60a5fa;
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.2);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: #fcd34d;
        }

        .timer-big {
            font-size: 2.5rem;
            font-weight: 700;
            color: #ff4800;
            text-align: center;
            padding: 1rem;
            background: rgba(255, 72, 0, 0.1);
            border-radius: 12px;
            margin: 1rem 0;
        }

        .weekend-info {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
            text-align: center;
        }

        .form-control, textarea {
            background: rgba(13, 13, 13, 0.6);
            border: 1px solid rgba(255, 72, 0, 0.2);
            border-radius: 12px;
            color: #ffffff;
            padding: 0.875rem 1rem;
        }

        .form-control:focus, textarea:focus {
            background: rgba(13, 13, 13, 0.8);
            border-color: #ff4800;
            box-shadow: 0 0 0 0.2rem rgba(255, 72, 0, 0.2);
            color: #ffffff;
        }

        .form-control:disabled, textarea:disabled {
            background: rgba(13, 13, 13, 0.3);
            color: rgba(255, 255, 255, 0.5);
        }

        .form-control::placeholder, textarea::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .btn {
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .btn-danger:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
            color: white;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
            color: white;
        }

        .btn-secondary {
            background: rgba(107, 114, 128, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.6);
        }

        .bg-light {
            background: rgba(255, 255, 255, 0.05) !important;
            color: rgba(255, 255, 255, 0.9);
        }

        .bg-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
            color: white !important;
        }

        .text-white {
            color: white !important;
        }

        .text-muted {
            color: rgba(255, 255, 255, 0.5) !important;
        }

        .text-danger {
            color: #ef4444 !important;
        }

        .border {
            border-color: rgba(255, 72, 0, 0.2) !important;
        }

        .rounded {
            border-radius: 12px !important;
        }

        hr {
            border-color: rgba(255, 72, 0, 0.2);
            margin: 2rem 0;
        }

        small {
            color: rgba(255, 255, 255, 0.5);
        }
    </style>

    <div class="container mt-4 project-container">

        <h3>Проєкт: {{ $project->name }}</h3>

        <div class="mb-3">
            <strong>Опис:</strong>
            <p>{{ $project->description ?? '—' }}</p>
        </div>

        {{-- Опис статусу --}}
        @if($currentStage)
            <p>
                <strong>Поточний етап:</strong> {{ $currentStage->stage->name }}
                @if($currentStage->start_date)
                    <br><small class="text-muted">
                        Початок: {{ $currentStage->start_date }}
                    </small>
                @endif
            </p>
        @endif

        <h4 class="mt-4">📌 Етапи проєкту</h4>

        @php
            $activeStage = $stageItems->firstWhere('stage_id', $project->status_id);
            $activePosition = $activeStage?->position ?? 999;
        @endphp

        <div class="stage-line">
            @foreach($stageItems as $item)
                @php
                    if ($item->position < $activePosition) {
                        $class = 'stage-done';
                    } elseif ($item->stage_id == $project->status_id) {
                        $class = 'stage-active';
                    } else {
                        $class = 'stage-future';
                    }
                @endphp

                <div class="stage-segment {{ $class }}">
                    {{ $item->stage->name }}
                </div>
            @endforeach
        </div>



        <div class="card p-4 mb-4">
            <h4 class="mb-3">⏳ Очікування від клієнта</h4>

            {{-- Немає очікування --}}
            @if(!$waitingActive)
                <div class="alert alert-success mb-0">
                    Зараз проєкт не чекає вашої відповіді.
                </div>
            @else

                {{-- ===================================================== --}}
                {{--  🔧 БЛОК КОНФІГУРАЦІЇ ДЛЯ clients-show.js (ОБОВ'ЯЗКОВО) --}}
                {{-- ===================================================== --}}
                <div id="timerConfig"
                     data-free-sec="{{ $freeLeftSec ?? 0 }}"
                     data-paid-sec="{{ $paidSec ?? 0 }}"
                     data-rate="{{ $project->rate ?? 0 }}"
                     data-status="{{ $waitingActive->status }}"
                     data-fetch-url="{{ route('project.waiting.status', $project->id) }}"
                     data-stop-url="{{ route('project.waiting.stop.client', $project->id) }}">
                </div>



                {{-- ========================= --}}
                {{--          RUNNING          --}}
                {{-- ========================= --}}
                @if($waitingActive->status === 'running')

                    <div class="alert alert-danger">
                        <b>Увага!</b> Проєкт призупинений.
                        <br>Команда очікує від вас інформацію.
                    </div>

                    {{-- FREE --}}
                    <div id="freeBox" class="mb-3">
                        <div class="alert alert-info mb-1"><b>Безкоштовне очікування:</b></div>
                        <div id="freeTimer" class="timer-big" data-seconds="{{ $freeLeftSec }}">
                            {{ $freeLeftSec !== null ? gmdate('H:i:s', $freeLeftSec) : '00:00:00' }}
                        </div>

                        <div class="weekend-info mt-2">
                            У вихідні час не рахується
                        </div>
                    </div>

                    {{-- PAID --}}
                    <div id="paidBox" class="mb-3" style="display:none;">
                        <div class="alert alert-danger mb-1"><b>Платний час очікування:</b></div>

                        <div id="paidTimer" class="timer-big" data-seconds="{{ $paidSec }}">
                            {{ $paidSec !== null ? gmdate('H:i:s', $paidSec) : '00:00:00' }}
                        </div>

                        <div class="weekend-info mt-2">У вихідні час не рахується</div>

                        <div class="mt-2">
                            <strong>Нараховано:</strong>
                            <span id="paidAmount" class="text-danger" style="font-size:20px;">€0.00</span>
                        </div>
                    </div>

                    <p class="text-muted">Запущено: {{ $waitingActive->started_at }}</p>

                    <p><b>Опис того, що очікується:</b></p>
                    <div class="p-3 bg-light border rounded mb-3">
                        {{ $waitingActive->admin_comment ?? '—' }}
                    </div>

                    <textarea class="form-control mb-2" id="clientStopComment"
                              placeholder="Ваша відповідь..." rows="3"></textarea>
                    <button class="btn btn-danger w-100" id="clientStopBtn">Надати інформацію</button>

                @endif



                {{-- ========================= --}}
                {{--          PENDING          --}}
                {{-- ========================= --}}
                @if($waitingActive->status === 'pending')

                    <div class="alert alert-warning">
                        Ваш коментар надіслано. Очікується підтвердження менеджера.
                    </div>

                    <textarea class="form-control mb-2" disabled rows="3">{{ $waitingActive->client_comment }}</textarea>

                    <button class="btn btn-secondary w-100" disabled>Очікуємо...</button>

                @endif



                {{-- ========================= --}}
                {{--         REJECTED          --}}
                {{-- ========================= --}}
                @if($waitingActive->status === 'rejected')

                    <div class="alert alert-danger">
                        <b>Менеджер відхилив відповідь:</b><br>
                        {{ $waitingActive->rejected_admin_comment }}
                    </div>

                    {{-- FREE --}}
                    <div id="freeBox" class="mb-3">
                        <div class="alert alert-info mb-1"><b>Безкоштовне очікування:</b></div>
                        <div id="freeTimer" class="timer-big" data-seconds="{{ $freeLeftSec }}">
                            {{ $freeLeftSec !== null ? gmdate('H:i:s', $freeLeftSec) : '00:00:00' }}
                        </div>
                        <small class="text-muted">У вихідні час не рахується</small>
                    </div>

                    {{-- PAID --}}
                    <div id="paidBox" class="mb-3" style="display:none;">
                        <div class="alert alert-danger mb-1"><b>Платний час очікування:</b></div>
                        <div id="paidTimer" class="timer-big" data-seconds="{{ $paidSec }}">
                            {{ $paidSec !== null ? gmdate('H:i:s', $paidSec) : '00:00:00' }}
                        </div>
                        <small class="text-muted">У вихідні час не рахується</small>

                        <div class="mt-2">
                            <strong>Нараховано:</strong>
                            <span id="paidAmount" class="text-danger" style="font-size:20px;">€0.00</span>
                        </div>
                    </div>

                    <textarea class="form-control mb-2" id="clientStopComment"
                              placeholder="Дайте уточнення..." rows="3"></textarea>
                    <button class="btn btn-danger w-100" id="clientStopBtn">Надіслати повторно</button>

                @endif



                {{-- ========================= --}}
                {{--         COMPLETED         --}}
                {{-- ========================= --}}
                @if($waitingActive->status === 'completed')

                    <div class="alert alert-success">
                        Очікування завершено. Команда повернулась до роботи.
                    </div>

                    <div class="display-5 mb-2">
                        {{ $waitingActive->completed_at }}
                    </div>

                @endif

            @endif {{-- waitingActive exists --}}
        </div>



        <h4 class="mt-4">📜 Історія очікувань</h4>

        @forelse($waitingHistory as $log)
            <div class="mb-4 p-3 border rounded">

                <div class="small text-muted mb-2">
                    {{ $log->created_at->format('d.m.Y H:i') }}
                    —
                    <b>
                        @if($log->status === 'running') Запущено очікування
                        @elseif($log->status === 'pending') Клієнт відповів (очікуємо)
                        @elseif($log->status === 'rejected') Відповідь відхилена
                        @elseif($log->status === 'completed') Завершено
                        @endif
                    </b>
                </div>

                @foreach($log->messages as $msg)
                    <div class="mt-2">
                        <div class="fw-bold">
                            @if($msg->from === 'client')
                                🧑‍💼 Клієнт відповів
                            @else
                                🛠 Команда відповіла
                            @endif
                        </div>

                        <div class="p-2 bg-light rounded mt-1">
                            {{ $msg->message }}
                        </div>
                    </div>
                @endforeach

            </div>
        @empty
            <p class="text-muted">Історії поки немає.</p>
        @endforelse


        <hr>

        <h4>💬 Чат з менеджером</h4>

        <div class="border p-3 mb-3" style="max-height:400px; overflow-y:auto;">
            @forelse($project->messages as $msg)
                <div class="p-2 mb-2 rounded {{ $msg->from_client ? 'bg-light' : 'bg-primary text-white' }}">
                    <strong>{{ $msg->from_client ? 'Ви' : 'Менеджер' }}:</strong>
                    <div>{{ $msg->message }}</div>
                    <small class="text-muted">{{ $msg->created_at->format('H:i d.m.Y') }}</small>
                </div>
            @empty
                <p class="text-muted">Повідомлень поки немає</p>
            @endforelse
        </div>

        <form method="POST" action="{{ route('client.projects.message', $project) }}">
            @csrf
            <textarea name="message" rows="3" class="form-control mb-2" placeholder="Напишіть повідомлення..."></textarea>
            <button type="submit" class="btn btn-primary">Надіслати</button>
        </form>

        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif

    </div>
@endsection

@vite(['resources/js/clients-show.js'])


{{--@section('scripts')--}}
{{--    <script>--}}
{{--        document.addEventListener('DOMContentLoaded', () => {--}}

{{--            @if($waitingActive && in_array($waitingActive->status, ['running','rejected']))--}}

{{--            // =============================--}}
{{--            // 1. Значення з PHP (правильно!)--}}
{{--            // =============================--}}
{{--            let freeSec = {{ $freeLeftSec ?? 0 }};--}}
{{--            let paidSec = {{ $paidSec ?? 0 }};--}}

{{--            const freeEl = document.getElementById('freeTimer');--}}
{{--            const paidEl = document.getElementById('paidTimer');--}}


{{--            // =============================--}}
{{--            // 2. Робочий день?--}}
{{--            // =============================--}}
{{--            function isWorkingTime() {--}}
{{--                const now = new Date();--}}
{{--                const day = now.getDay(); // 0 = Sun, 6 = Sat--}}

{{--                if (day === 0 || day === 6) return false;--}}

{{--                const h = now.getHours();--}}
{{--                const m = now.getMinutes();--}}

{{--                // Заборона 00:00 — відлік з 00:01--}}
{{--                if (h === 0 && m === 0) return false;--}}

{{--                return true;--}}
{{--            }--}}

{{--            // =============================--}}
{{--            // 3. Формат часу--}}
{{--            // =============================--}}
{{--            function format(sec) {--}}
{{--                const h = String(Math.floor(sec / 3600)).padStart(2, '0');--}}
{{--                const m = String(Math.floor((sec % 3600) / 60)).padStart(2, '0');--}}
{{--                const s = String(sec % 60).padStart(2, '0');--}}
{{--                return `${h}:${m}:${s}`;--}}
{{--            }--}}


{{--            // =============================--}}
{{--            // 4. Перемикач видимості блоків--}}
{{--            // =============================--}}
{{--            function updateVisibility() {--}}
{{--                if (freeSec > 0) {--}}
{{--                    freeBox.style.display = 'block';--}}
{{--                    paidBox.style.display = 'none';--}}
{{--                } else {--}}
{{--                    freeBox.style.display = 'none';--}}
{{--                    paidBox.style.display = 'block';--}}
{{--                }--}}
{{--            }--}}

{{--            const freeBox = document.getElementById('freeBox');--}}
{{--            const paidBox = document.getElementById('paidBox');--}}

{{--            updateVisibility();--}}


{{--            // =============================--}}
{{--            // 5. Основний тік таймера--}}
{{--            // =============================--}}
{{--            setInterval(() => {--}}

{{--                if (!isWorkingTime()) return;--}}

{{--                if (freeSec > 0) {--}}
{{--                    freeSec--;--}}
{{--                    freeEl.textContent = format(freeSec);--}}
{{--                } else {--}}
{{--                    paidSec++;--}}
{{--                    paidEl.textContent = format(paidSec);--}}

{{--                    const rate = {{ $project->rate ?? 0 }};--}}
{{--                    if (rate > 0) {--}}
{{--                        const amount = (paidSec / 3600) * rate;--}}
{{--                        document.getElementById('paidAmount').textContent = '€' + amount.toFixed(2);--}}
{{--                    }--}}
{{--                }--}}

{{--                updateVisibility();--}}

{{--            }, 1000);--}}


{{--            // =============================--}}
{{--            // 6. Легка синхронізація раз на 30 сек (без DOMParser!)--}}
{{--            // =============================--}}
{{--            setInterval(() => {--}}
{{--                fetch("{{ route('project.waiting.status', $project->id) }}")--}}
{{--                    .then(r => r.json())--}}
{{--                    .then(data => {--}}

{{--                        // Якщо змінився статус → перезавантажити--}}
{{--                        const current = "{{ $waitingActive->status }}";--}}
{{--                        if (data.status !== current) {--}}
{{--                            location.reload();--}}
{{--                        }--}}

{{--                        // Самі freeSec/paidSec НЕ скидаємо,--}}
{{--                        // PHP рахує лише під час оновлення сторінки.--}}
{{--                    });--}}
{{--            }, 30000);--}}

{{--            @endif--}}


{{--            // =============================--}}
{{--            // 7. STOP BUTTON--}}
{{--            // =============================--}}
{{--            const stopBtn = document.getElementById('clientStopBtn');--}}
{{--            const commentField = document.getElementById('clientStopComment');--}}

{{--            stopBtn?.addEventListener('click', () => {--}}

{{--                let comment = commentField.value.trim();--}}
{{--                if (!comment.length) {--}}
{{--                    alert("Напишіть коментар");--}}
{{--                    return;--}}
{{--                }--}}

{{--                stopBtn.disabled = true;--}}

{{--                fetch("{{ route('project.waiting.stop.client', $project->id) }}", {--}}
{{--                    method: "POST",--}}
{{--                    headers: {--}}
{{--                        "X-CSRF-TOKEN": "{{ csrf_token() }}",--}}
{{--                        "Content-Type": "application/json",--}}
{{--                    },--}}
{{--                    body: JSON.stringify({ comment })--}}
{{--                })--}}
{{--                    .then(r => r.json())--}}
{{--                    .then(d => {--}}
{{--                        if (d.success) location.reload();--}}
{{--                        else {--}}
{{--                            stopBtn.disabled = false;--}}
{{--                            alert(d.error ?? "Помилка");--}}
{{--                        }--}}
{{--                    })--}}
{{--                    .catch(() => {--}}
{{--                        stopBtn.disabled = false;--}}
{{--                        alert("Помилка мережі");--}}
{{--                    });--}}
{{--            });--}}

{{--        });--}}
{{--    </script>--}}
{{--@endsection--}}



