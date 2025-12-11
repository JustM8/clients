@extends('layouts.app')

@section('content')
<style>
    .page-header {
        padding: 2rem 0;
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #ff4800 0%, #ff6b00 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .form-card {
        background: rgba(42, 42, 42, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 72, 0, 0.2);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
    }

    .section-title {
        color: #ff4800;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid rgba(255, 72, 0, 0.3);
    }

    .form-label {
        color: rgba(255, 255, 255, 0.9);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        background: rgba(13, 13, 13, 0.6);
        border: 1px solid rgba(255, 72, 0, 0.2);
        border-radius: 12px;
        color: #ffffff;
        padding: 0.875rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        background: rgba(13, 13, 13, 0.8);
        border-color: #ff4800;
        box-shadow: 0 0 0 0.2rem rgba(255, 72, 0, 0.2);
        color: #ffffff;
    }

    .form-select option {
        background: #1a1a1a;
        color: #ffffff;
    }

    .table {
        color: rgba(255, 255, 255, 0.9);
    }

    .table thead th {
        background: rgba(255, 72, 0, 0.1);
        color: #ff4800;
        border-color: rgba(255, 72, 0, 0.2);
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table tbody td {
        border-color: rgba(255, 72, 0, 0.1);
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: rgba(255, 72, 0, 0.05);
    }

    .badge {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
    }

    .bg-secondary {
        background: rgba(107, 114, 128, 0.3) !important;
    }

    .bg-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
        color: white;
    }

    .btn {
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-success {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        border: none;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: none;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
    }

    .btn-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        border: none;
        color: white;
    }

    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
        color: white;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border: none;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
    }

    .btn-secondary {
        background: rgba(107, 114, 128, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: rgba(255, 255, 255, 0.9);
    }

    .btn-secondary:hover {
        background: rgba(107, 114, 128, 0.5);
        color: white;
    }

    .btn-outline-secondary {
        background: transparent;
        border: 1px solid rgba(255, 72, 0, 0.3);
        color: #ff4800;
    }

    .btn-outline-secondary:hover {
        background: rgba(255, 72, 0, 0.1);
        border-color: #ff4800;
        color: #ff4800;
    }

    .alert {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.5rem;
    }

    .alert-danger {
        background: rgba(239, 68, 68, 0.2);
        color: #fca5a5;
    }

    .alert-warning {
        background: rgba(245, 158, 11, 0.2);
        color: #fcd34d;
    }

    .chat-message {
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.75rem;
    }

    .bg-light {
        background: rgba(255, 255, 255, 0.05) !important;
    }

    .text-muted {
        color: rgba(255, 255, 255, 0.5) !important;
    }

    .text-white {
        color: white !important;
    }

    #timerDisplay {
        font-size: 1.5rem;
        font-weight: 700;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
    }
</style>

<div class="container mt-4">
    <div class="page-header">
        <h1 class="page-title">{{ __('admin/projects.edit.title', ['name' => $project->name]) }}</h1>
    </div>

    {{-- якщо адмін --}}
    @if(Auth::user()->role === 'admin')
        <form action="{{ route('admin.projects.update', $project) }}" method="POST">
            @csrf @method('PUT')

            <div class="form-card">
                <div class="mb-3">
                    <label class="form-label">{{ __('admin/projects.edit.fields.name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ $project->name }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('admin/projects.edit.fields.description') }}</label>
                    <textarea name="description" class="form-control" rows="3">{{ $project->description }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('admin/projects.edit.fields.type') }}</label>
                        <select name="type_id" class="form-select">
                            <option value="">—</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" @selected($project->type_id == $type->id)>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('admin/projects.edit.fields.client') }}</label>
                        <select name="client_id" class="form-select">
                            <option value="">—</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" @selected($client->id == $project->client_id)>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <h5 class="section-title">{{ __('admin/projects.edit.timer_card.title') }}</h5>

                <div class="d-flex gap-3 align-items-center flex-wrap">
                    <div class="flex-grow-1">
                        <select id="stageSelect" class="form-select" name="status_id">
                            @foreach($project->stageItems as $item)
                                <option value="{{ $item->stage_id }}" @selected($project->status_id == $item->stage_id)>
                                    {{ __('admin/projects.stages.' . $item->stage->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="button" class="btn btn-success" id="startTimerBtn">
                        ▶️ {{ __('admin/projects.edit.timer_card.start') }}
                    </button>

                    <button type="button" class="btn btn-danger" id="stopTimerBtn" disabled>
                        ⏹️ {{ __('admin/projects.edit.timer_card.stop') }}
                    </button>

                    <span id="timerDisplay" class="badge bg-primary">
                        00:00:00
                    </span>
                </div>
            </div>

            <div class="form-card">
                <h5 class="section-title">{{ __('admin/projects.edit.stages.title') }}</h5>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>{{ __('admin/projects.edit.stages.table.name') }}</th>
                            <th>{{ __('admin/projects.edit.stages.table.start') }}</th>
                            <th>{{ __('admin/projects.edit.stages.table.end') }}</th>
                            <th>{{ __('admin/projects.edit.stages.table.expected') }}</th>
                            <th>{{ __('admin/projects.edit.stages.table.spent') }}</th>
                            <th>{{ __('admin/projects.edit.stages.table.actions') }}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($project->stageItems as $item)
                            <tr id="row-existing-{{ $item->id }}">
                                <td>{{ $item->display_name }}</td>
                                <td><input type="date" name="stage[{{ $item->id }}][start_date]" value="{{ $item->start_date }}" class="form-control"></td>
                                <td><input type="date" name="stage[{{ $item->id }}][end_date]" value="{{ $item->end_date }}" class="form-control"></td>
                                <td><span class="badge bg-secondary">{{ $item->expected_end_date }}</span></td>
                                <td data-stage-id="{{ $item->stage_id }}">
                                    {{ gmdate('H:i:s', $item->spent_seconds) }}
                                </td>
                                <td>
                                    @if($item->custom)
                                        <button type="button"
                                                class="btn btn-sm btn-danger"
                                                onclick="removeExistingStage({{ $item->id }})">
                                            {{ __('admin/projects.edit.stages.table.delete') }}
                                        </button>
                                        <input type="hidden" name="delete_stage_ids[]" value="">
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <button id="addStageBtn" type="button" class="btn btn-outline-secondary mb-3">
                    ➕ {{ __('admin/projects.edit.stages.add_button') }}
                </button>

                <table class="table">
                    <tbody id="newStagesContainer"></tbody>
                </table>
            </div>

            <div class="form-card">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('admin/projects.edit.fields.rate') }}</label>
                        <input type="number" name="rate" class="form-control"
                               value="{{ $project->rate }}" step="0.01" min="0">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('admin/projects.edit.fields.buffer_hours') }}</label>
                        <input type="number" name="buffer_hours" class="form-control"
                               value="{{ $project->buffer_hours ?? 48 }}">
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <button class="btn btn-success">
                        ✅ {{ __('admin/projects.edit.buttons.save') }}
                    </button>

                    <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">
                        ← {{ __('admin/projects.edit.buttons.back') }}
                    </a>
                </div>
            </div>
        </form>

        <div class="form-card">
            <h5 class="section-title">{{ __('admin/projects.waiting.title') }}</h5>

            {{-- Контейнер очікування --}}
            <div id="waiting-start"
                 @if($waitingActive && in_array($waitingActive->status, ['running', 'pending', 'rejected']))
                     style="display:none"
                @endif>
                <div class="mb-2">
                    <label class="form-label">{{ __('admin/projects.waiting.start.label') }}</label>
                    <textarea id="waitingComment" class="form-control" rows="2"
                              placeholder="{{ __('admin/projects.waiting.start.placeholder') }}"></textarea>
                </div>

                <button id="waitingStartBtn" type="button" class="btn btn-warning">
                    ▶️ {{ __('admin/projects.waiting.start.button') }}
                </button>
            </div>

            {{-- Активне або завершене очікування --}}
            <div id="waiting-active"
                 style="display: {{ $waitingActive ? 'block' : 'none' }};"
                 data-start="{{ $waitingActive? strtotime($waitingActive->started_at) : '' }}"
            >
                @if($waitingActive)

                    {{-- RUNNING --}}
                    @if($waitingActive->status === 'running')
                        <div class="alert alert-danger">
                            <b>{{ __('admin/projects.waiting.running.title') }}</b>
                        </div>

                        <div class="fs-4 mb-2" id="waitingTimer">00:00:00</div>
                        <p class="text-muted">{{ __('admin/projects.waiting.running.started_at') }}: {{ $waitingActive->started_at }}</p>

                        <b>{{ __('admin/projects.waiting.running.comment') }}</b>
                        <div class="p-2 bg-light border rounded mb-2">
                            {{ $waitingActive->admin_comment }}
                        </div>
                    @endif

                    {{-- PENDING --}}
                    @if($waitingActive->status === 'pending')
                        <div class="alert alert-warning mt-3">
                            <b>{{ __('admin/projects.waiting.pending.title') }}</b>
                        </div>

                        <b>{{ __('admin/projects.waiting.pending.comment') }}</b>
                        <div class="p-2 bg-light border rounded mb-2">
                            {{ $waitingActive->client_comment }}
                        </div>

                        <p class="text-muted">{{ __('admin/projects.waiting.pending.started_at') }}: {{ $waitingActive->started_at }}</p>
                    @endif

                    {{-- REJECTED --}}
                    @if($waitingActive->status === 'rejected')
                        <div class="alert alert-danger">
                            <b>{{ __('admin/projects.waiting.rejected.title') }}</b>
                        </div>

                        <b>{{ __('admin/projects.waiting.rejected.reason') }}</b>
                        <div class="p-2 bg-light border rounded mb-3">
                            {{ $waitingActive->rejected_admin_comment }}
                        </div>

                        <div class="fs-4 mb-2" id="waitingTimer">00:00:00</div>

                        <p class="text-muted">{{ __('admin/projects.waiting.rejected.started_at') }}: {{ $waitingActive->started_at }}</p>
                    @endif

                @endif
            </div>
        </div>

        <div class="form-card">
            <h5 class="section-title">{{ __('admin/projects.waiting.history_title') }}</h5>

            @forelse($waitingHistory as $log)
                <div class="mb-4 p-3 border rounded" style="border-color: rgba(255, 72, 0, 0.2) !important;">

                    {{-- ДАТА + СТАТУС --}}
                    <div class="small text-muted mb-2">
                        {{ $log->created_at->format('d.m.Y H:i') }}
                        — <b>
                            @lang('admin/projects.waiting.history_status.' . $log->status)
                        </b>
                    </div>

                    {{-- ВСІ ПОВІДОМЛЕННЯ ЦЬОГО ЦИКЛУ --}}
                    @foreach($log->messages as $msg)
                        <div class="mt-2">
                            <div class="fw-bold">
                                @if($msg->from === 'client')
                                    👤 {{ __('admin/projects.waiting.messages.client') }}
                                @else
                                    🛠 {{ __('admin/projects.waiting.messages.admin') }}
                                @endif
                            </div>

                            <div class="p-2 bg-light rounded mt-1">
                                {{ $msg->message }}
                            </div>

                        </div>
                    @endforeach

                </div>
            @empty
                <p class="text-muted">{{ __('admin/projects.waiting.messages.empty') }}</p>
            @endforelse
        </div>

    @endif

    <div class="form-card">
        <h5 class="section-title">{{ __('admin/projects.chat.title') }}</h5>

        <div class="border p-3 mb-3 rounded" style="max-height:400px; overflow-y:auto; border-color: rgba(255, 72, 0, 0.2) !important;">
            @forelse($project->messages as $msg)
                <div class="chat-message {{ $msg->from_client ? 'bg-light' : 'bg-primary text-white' }}">
                    <strong>
                        {{ $msg->from_client ? __('admin/projects.chat.message.client') : __('admin/projects.chat.message.admin') }}:
                    </strong>
                    <div>{{ $msg->message }}</div>
                    <small class="text-muted">{{ $msg->created_at->format('H:i d.m.Y') }}</small>
                </div>
            @empty
                <p class="text-muted">{{ __('admin/projects.chat.empty') }}</p>
            @endforelse
        </div>

        {{-- Форма чату доступна лише клієнту --}}
        @if(Auth::user()->role === 'client')
            <form method="POST" action="{{ route('project.message.send', $project->id) }}">
                @csrf
                <textarea name="message" rows="3" class="form-control mb-2"
                          placeholder="{{ __('admin/projects.chat.form.placeholder') }}"></textarea>

                <button type="submit" class="btn btn-primary">
                    {{ __('admin/projects.chat.form.send') }}
                </button>
            </form>
        @endif
    </div>
</div>
@endsection

@section('scripts')

<script>
    // ⬅️ Ось ця змінна обовʼязково має бути оголошена!
    const ALL_STAGES = @json($allStages);

    document.addEventListener('DOMContentLoaded', () => {
        const addBtn = document.getElementById('addStageBtn');
        const container = document.getElementById('newStagesContainer');
        let counter = 0;

        if (!addBtn || !container) return;

        addBtn.addEventListener('click', () => {
            counter++;

            let options = '<option value="">Оберіть етап</option>';
            ALL_STAGES.forEach(s => {
                options += `<option value="${s.id}">${s.name}</option>`;
            });

            const row = document.createElement('tr');
            row.innerHTML = `
            <td style="width:220px">
                <select name="new_stage[${counter}][stage_id]" class="form-select" required>
                    ${options}
                </select>
            </td>

            <td>
                <input type="date"
                       name="new_stage[${counter}][start_date]"
                       class="form-control">
            </td>

            <td>
                <input type="date"
                       name="new_stage[${counter}][end_date]"
                       class="form-control">
            </td>

            <td><span class="badge bg-secondary">—</span></td>

            <td>00:00:00</td>

            <td>
                <button type="button"
                        class="btn btn-sm btn-danger"
                        onclick="this.closest('tr').remove()">
                    ✖
                </button>
            </td>
        `;

            container.appendChild(row);
        });

    });


</script>
<script>
    function removeExistingStage(id) {

        // шукаємо прихований інпут саме в цьому рядку
        const row = document.getElementById('row-existing-' + id);

        if (!row) return;

        const hidden = row.querySelector('input[name="delete_stage_ids[]"]');

        if (hidden) {
            hidden.value = id; // ставимо ID у прихований інпут
        }

        // приховуємо рядок в таблиці
        row.style.display = 'none';
    }
</script>


{{--timer project--}}
<script>
    document.addEventListener('DOMContentLoaded', () => {

        const startBtn = document.getElementById("startTimerBtn");
        const stopBtn = document.getElementById("stopTimerBtn");
        const timerDisplay = document.getElementById("timerDisplay");
        const stageSelect = document.getElementById("stageSelect");

        let timerInterval = null;

        function format(sec) {
            const h = String(Math.floor(sec / 3600)).padStart(2,'0');
            const m = String(Math.floor((sec % 3600) / 60)).padStart(2,'0');
            const s = String(sec % 60).padStart(2,'0');
            return `${h}:${m}:${s}`;
        }

        function startLocalCounter(timestampStart) {
            stopLocalCounter();

            timerInterval = setInterval(() => {
                const diff = Math.floor((Date.now() - timestampStart) / 1000);
                timerDisplay.innerText = format(diff);
            }, 1000);
        }

        function stopLocalCounter() {
            if (timerInterval) clearInterval(timerInterval);
            timerInterval = null;
        }

        // Start timer
        startBtn.addEventListener("click", () => {
            fetch(`{{ route('project.timer.start', $project->id) }}`, {
                method: "POST",
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                body: new URLSearchParams({stage_item_id: stageSelect.value})
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        stageSelect.disabled = true;
                        startBtn.disabled = true;
                        stopBtn.disabled = false;
                        startLocalCounter(Date.now());
                    }
                });
        });

        // Stop timer
        stopBtn.addEventListener("click", () => {
            fetch(`{{ route('project.timer.stop', $project->id) }}`, {
                method: "POST",
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        startBtn.disabled = false;
                        stopBtn.disabled = true;
                        stopLocalCounter();
                        stageSelect.disabled = false;
                        // 🟦 ОНОВЛЮЄМО ВИТРАЧЕНО У ГОЛОВНІЙ ТАБЛИЦІ
                        // знайти рядок за stage_id
                        const selected = stageSelect.value;
                        const cell = document.querySelector(`td[data-stage-id="${selected}"]`);

                        if (cell && data.spent) {
                            cell.textContent = data.spent;
                        }
                    }
                });
        });

        // Load initial status
        fetch(`{{ route('project.timer.status', $project->id) }}`)
            .then(r => r.json())
            .then(data => {

                // // 🟦 ВИСТАВЛЯЄМО АКТИВНИЙ ЕТАП У СЕЛЕКТ ЯКЩО Є stage_id
                // if (data.stage_id) {
                //     stageSelect.value = data.stage_id;
                // }

                // 🟥 ТАЙМЕР НЕ ЙДЕ
                if (!data.running) {
                    startBtn.disabled = false;
                    stopBtn.disabled = true;
                    timerDisplay.innerText = '00:00:00';
                    return;
                }

                // 🟩 ТАЙМЕР АКТИВНИЙ
                startBtn.disabled = true;
                stopBtn.disabled = false;

                const startedAt = new Date(data.timer.started_at).getTime();
                startLocalCounter(startedAt);
            });


    });
</script>
{{--timer client--}}
<script>
    document.addEventListener('DOMContentLoaded', () => {

        const startBtn         = document.getElementById('waitingStartBtn');
        const commentField     = document.getElementById('waitingComment');

        const waitingStartBox  = document.getElementById('waiting-start');
        const waitingActiveBox = document.getElementById('waiting-active');
        const waitingTimer     = document.getElementById('waitingTimer');

        let timerInterval = null;

        function startLocalCounter(startedAtMs) {
            if (!waitingTimer) return;
            if (timerInterval) clearInterval(timerInterval);

            const BUFFER = ({{ $project->buffer_hours ?? 48 }}) * 3600; // годин → секунди

            timerInterval = setInterval(() => {
                const now  = Date.now();
                const rawDiff = Math.floor((now - startedAtMs) / 1000);

                const diff = rawDiff - BUFFER;
                const sign = diff < 0 ? '-' : '';

                const d = Math.abs(diff);

                const h = String(Math.floor(d / 3600)).padStart(2, '0');
                const m = String(Math.floor((d % 3600) / 60)).padStart(2, '0');
                const s = String(d % 60).padStart(2, '0');

                waitingTimer.textContent = `${sign}${h}:${m}:${s}`;
            }, 1000);
        }


        // ---- Автостарт при перезавантаженні ----
        if (waitingActiveBox && waitingTimer) {
            const start = parseInt(waitingActiveBox.dataset.start);
            if (start > 0) {
                startLocalCounter(start * 1000);
            }
        }

        // ---- Кнопка старт ----
        startBtn?.addEventListener('click', () => {
            const comment = commentField.value.trim();
            if (!comment.length) {
                alert("Введіть коментар");
                return;
            }

            startBtn.disabled = true;

            fetch("{{ route('project.waiting.start', $project->id) }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ comment }),
            })
                .then(r => r.json())
                .then(data => {
                    startBtn.disabled = false;

                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    // Щоб нічого не ламати й не городити верстку через JS —
                    // просто перезавантажуємо сторінку і бачимо блок RUNNING.
                    location.reload();
                });
        });
    });
</script>


@endsection
