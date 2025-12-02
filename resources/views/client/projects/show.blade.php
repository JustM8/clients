@extends('layouts.app')

@section('content')
    <div class="container mt-4" style="background: white">

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
                        $class = 'stage-done';        // завершені етапи

                    } elseif ($item->stage_id == $project->status_id) {
                        $class = 'stage-active';      // поточний етап

                    } else {
                        $class = 'stage-future';      // майбутні
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

                {{-- ========================= --}}
                {{--          RUNNING          --}}
                {{-- ========================= --}}
                @if($waitingActive->status === 'running')

                    <div class="alert alert-danger">
                        <b>Увага!</b> Проєкт призупинений.
                        <br>Команда очікує від вас інформацію.
                    </div>
{{--                    {{ dd($freeLeftSec, $paidSec, $bufferEnd, $waitingActive->started_at) }}--}}
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

                        <div class="weekend-info mt-2">
                            У вихідні час не рахується
                        </div>

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

                    <textarea class="form-control mb-2" disabled rows="3">
                {{ $waitingActive->client_comment }}
            </textarea>

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

                {{-- ДАТА + СТАТУС --}}
                <div class="small text-muted mb-2">
                    {{ $log->created_at->format('d.m.Y H:i') }}
                    — <b>
                        @if($log->status === 'running') Запущено очікування
                        @elseif($log->status === 'pending') Клієнт відповів (очікуємо)
                        @elseif($log->status === 'rejected') Відповідь відхилена
                        @elseif($log->status === 'completed') Завершено
                        @endif
                    </b>
                </div>

                {{-- ВСІ ПОВІДОМЛЕННЯ ЦЬОГО ЦИКЛУ ОЧІКУВАННЯ --}}
                @foreach($log->messages as $msg)
                    <div class="mt-2">

                        {{-- Хедер хто писав --}}
                        <div class="fw-bold">
                            @if($msg->from === 'client')
                                🧑‍💼 Клієнт відповів
                            @else
                                🛠 Команда відповіла
                            @endif
                        </div>

                        {{-- Текст повідомлення --}}
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
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            @if($waitingActive && in_array($waitingActive->status, ['running','rejected']))

            // =============================
            // 1. Значення з PHP (правильно!)
            // =============================
            let freeSec = {{ $freeLeftSec ?? 0 }};
            let paidSec = {{ $paidSec ?? 0 }};

            const freeEl = document.getElementById('freeTimer');
            const paidEl = document.getElementById('paidTimer');


            // =============================
            // 2. Робочий день?
            // =============================
            function isWorkingTime() {
                const now = new Date();
                const day = now.getDay(); // 0 = Sun, 6 = Sat

                if (day === 0 || day === 6) return false;

                const h = now.getHours();
                const m = now.getMinutes();

                // Заборона 00:00 — відлік з 00:01
                if (h === 0 && m === 0) return false;

                return true;
            }

            // =============================
            // 3. Формат часу
            // =============================
            function format(sec) {
                const h = String(Math.floor(sec / 3600)).padStart(2, '0');
                const m = String(Math.floor((sec % 3600) / 60)).padStart(2, '0');
                const s = String(sec % 60).padStart(2, '0');
                return `${h}:${m}:${s}`;
            }


            // =============================
            // 4. Перемикач видимості блоків
            // =============================
            function updateVisibility() {
                if (freeSec > 0) {
                    freeBox.style.display = 'block';
                    paidBox.style.display = 'none';
                } else {
                    freeBox.style.display = 'none';
                    paidBox.style.display = 'block';
                }
            }

            const freeBox = document.getElementById('freeBox');
            const paidBox = document.getElementById('paidBox');

            updateVisibility();


            // =============================
            // 5. Основний тік таймера
            // =============================
            setInterval(() => {

                if (!isWorkingTime()) return;

                if (freeSec > 0) {
                    freeSec--;
                    freeEl.textContent = format(freeSec);
                } else {
                    paidSec++;
                    paidEl.textContent = format(paidSec);

                    const rate = {{ $project->rate ?? 0 }};
                    if (rate > 0) {
                        const amount = (paidSec / 3600) * rate;
                        document.getElementById('paidAmount').textContent = '€' + amount.toFixed(2);
                    }
                }

                updateVisibility();

            }, 1000);


            // =============================
            // 6. Легка синхронізація раз на 30 сек (без DOMParser!)
            // =============================
            setInterval(() => {
                fetch("{{ route('project.waiting.status', $project->id) }}")
                    .then(r => r.json())
                    .then(data => {

                        // Якщо змінився статус → перезавантажити
                        const current = "{{ $waitingActive->status }}";
                        if (data.status !== current) {
                            location.reload();
                        }

                        // Самі freeSec/paidSec НЕ скидаємо,
                        // PHP рахує лише під час оновлення сторінки.
                    });
            }, 30000);

            @endif


            // =============================
            // 7. STOP BUTTON
            // =============================
            const stopBtn = document.getElementById('clientStopBtn');
            const commentField = document.getElementById('clientStopComment');

            stopBtn?.addEventListener('click', () => {

                let comment = commentField.value.trim();
                if (!comment.length) {
                    alert("Напишіть коментар");
                    return;
                }

                stopBtn.disabled = true;

                fetch("{{ route('project.waiting.stop.client', $project->id) }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
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
    </script>
@endsection



