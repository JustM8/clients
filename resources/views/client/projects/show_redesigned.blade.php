@extends('layouts.app')

@section('content')
<style>
    .project-header {
        padding: 2rem 0;
        margin-bottom: 2rem;
    }

    .project-title {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #ff4800 0%, #ff6b00 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .info-card {
        background: rgba(42, 42, 42, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 72, 0, 0.2);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .section-title {
        color: #ff4800;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .alert {
        border-radius: 12px;
        border: none;
        padding: 1rem;
    }

    .alert-success {
        background: rgba(34, 197, 94, 0.2);
        color: #22c55e;
    }

    .alert-danger {
        background: rgba(239, 68, 68, 0.2);
        color: #fca5a5;
    }

    .alert-info {
        background: rgba(59, 130, 246, 0.2);
        color: #60a5fa;
    }

    .alert-warning {
        background: rgba(245, 158, 11, 0.2);
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

    .form-control, .form-select {
        background: rgba(13, 13, 13, 0.6);
        border: 1px solid rgba(255, 72, 0, 0.2);
        border-radius: 12px;
        color: #ffffff;
        padding: 0.875rem 1rem;
    }

    .form-control:focus, .form-select:focus {
        background: rgba(13, 13, 13, 0.8);
        border-color: #ff4800;
        box-shadow: 0 0 0 0.2rem rgba(255, 72, 0, 0.2);
        color: #ffffff;
    }

    .btn-success {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
    }

    .btn-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
    }

    .chat-message {
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.75rem;
    }

    .bg-light {
        background: rgba(255, 255, 255, 0.05) !important;
    }

    .bg-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
    }

    .text-white {
        color: white !important;
    }

    .text-muted {
        color: rgba(255, 255, 255, 0.5) !important;
    }

    .weekend-info {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.9rem;
        text-align: center;
    }
</style>

<div class="container mt-4">
    <div class="project-header">
        <h1 class="project-title">Проєкт: {{ $project->name }}</h1>
    </div>

    <div class="info-card">
        <div class="mb-3">
            <strong>Опис:</strong>
            <p>{{ $project->description ?? '—' }}</p>
        </div>

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
    </div>

    <div class="info-card">
        <h4 class="section-title">📌 Етапи проєкту</h4>
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
    </div>

    <div class="info-card">
        <h4 class="section-title">⏳ Очікування від клієнта</h4>

        @if(!$waitingActive)
            <div class="alert alert-success mb-0">
                Зараз проєкт не чекає вашої відповіді.
            </div>
        @else
            @if($waitingActive->status === 'running')
                <div class="alert alert-danger">
                    <b>Увага!</b> Проєкт призупинений. Команда очікує від вас інформацію.
                </div>

                <div id="freeBox" class="mb-3">
                    <div class="alert alert-info mb-1"><b>Безкоштовне очікування:</b></div>
                    <div id="freeTimer" class="timer-big" data-seconds="{{ $freeLeftSec }}">
                        {{ $freeLeftSec !== null ? gmdate('H:i:s', $freeLeftSec) : '00:00:00' }}
                    </div>
                    <div class="weekend-info mt-2">У вихідні час не рахується</div>
                </div>

                <div id="paidBox" class="mb-3" style="display:none;">
                    <div class="alert alert-danger mb-1"><b>Платний час очікування:</b></div>
                    <div id="paidTimer" class="timer-big" data-seconds="{{ $paidSec }}">
                        {{ $paidSec !== null ? gmdate('H:i:s', $paidSec) : '00:00:00' }}
                    </div>
                    <div class="weekend-info mt-2">У вихідні час не рахується</div>
                    <div class="mt-2">
                        <b>Вартість (годинна ставка):</b> {{ $project->rate ?? 0 }} грн/год
                    </div>
                </div>

                <div class="mb-2"><b>Коментар адміна:</b></div>
                <div class="p-2 bg-light border rounded mb-3" style="border-color: rgba(255, 72, 0, 0.2) !important;">
                    {{ $waitingActive->admin_comment }}
                </div>

                <form method="POST" action="{{ route('project.waiting.respond', $project->id) }}">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Ваша відповідь / коментар:</label>
                        <textarea name="comment" rows="3" class="form-control" required></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" name="action" value="approve" class="btn btn-success">
                            ✅ Підтвердити / Надіслати інфо
                        </button>
                        <button type="submit" name="action" value="reject" class="btn btn-danger">
                            ❌ Відхилити
                        </button>
                    </div>
                </form>
            @endif

            @if($waitingActive->status === 'pending')
                <div class="alert alert-warning mt-3">
                    <b>Ви вже відповіли.</b> Очікується рішення адміна.
                </div>
                <div class="mb-2"><b>Ваш коментар:</b></div>
                <div class="p-2 bg-light border rounded" style="border-color: rgba(255, 72, 0, 0.2) !important;">
                    {{ $waitingActive->client_comment }}
                </div>
            @endif
        @endif
    </div>

    <div class="info-card">
        <h5 class="section-title">💬 Чат проєкту</h5>
        <div class="border p-3 mb-3 rounded" style="max-height:400px; overflow-y:auto; border-color: rgba(255, 72, 0, 0.2) !important;">
            @forelse($project->messages as $msg)
                <div class="chat-message {{ $msg->from_client ? 'bg-light' : 'bg-primary text-white' }}">
                    <strong>{{ $msg->from_client ? '👤 Клієнт:' : '🛠 Адмін:' }}</strong>
                    <div>{{ $msg->message }}</div>
                    <small class="text-muted">{{ $msg->created_at->format('H:i d.m.Y') }}</small>
                </div>
            @empty
                <p class="text-muted">Повідомлень ще немає</p>
            @endforelse
        </div>

        <form method="POST" action="{{ route('project.message.send', $project->id) }}">
            @csrf
            <textarea name="message" rows="3" class="form-control mb-2" placeholder="Напишіть повідомлення..."></textarea>
            <button type="submit" class="btn btn-primary">Надіслати</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const freeBox = document.getElementById('freeBox');
        const paidBox = document.getElementById('paidBox');
        const freeTimer = document.getElementById('freeTimer');
        const paidTimer = document.getElementById('paidTimer');

        if (!freeTimer || !paidTimer) return;

        let freeSec = parseInt(freeTimer.dataset.seconds) || 0;
        let paidSec = parseInt(paidTimer.dataset.seconds) || 0;

        function formatTime(sec) {
            const h = String(Math.floor(Math.abs(sec) / 3600)).padStart(2,'0');
            const m = String(Math.floor((Math.abs(sec) % 3600) / 60)).padStart(2,'0');
            const s = String(Math.abs(sec) % 60).padStart(2,'0');
            return (sec < 0 ? '-' : '') + `${h}:${m}:${s}`;
        }

        setInterval(() => {
            const now = new Date();
            const day = now.getDay();
            const isWeekend = (day === 0 || day === 6);

            if (!isWeekend) {
                if (freeSec > 0) {
                    freeSec--;
                    freeTimer.textContent = formatTime(freeSec);
                } else {
                    if (freeBox) freeBox.style.display = 'none';
                    if (paidBox) paidBox.style.display = 'block';
                    paidSec++;
                    paidTimer.textContent = formatTime(paidSec);
                }
            }
        }, 1000);
    });
</script>
@endsection
