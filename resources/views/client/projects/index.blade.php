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

    .table-card {
        background: rgba(42, 42, 42, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 72, 0, 0.2);
        border-radius: 16px;
        padding: 1.5rem;
    }

    .table {
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 0;
    }

    .table thead th {
        background: rgba(255, 72, 0, 0.1);
        color: #ff4800;
        border-color: rgba(255, 72, 0, 0.2);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        padding: 1rem;
    }

    .table tbody td {
        border-color: rgba(255, 72, 0, 0.1);
        padding: 1rem;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: rgba(255, 72, 0, 0.05);
    }

    .alert-info {
        background: rgba(59, 130, 246, 0.2);
        border: 1px solid rgba(59, 130, 246, 0.3);
        color: #60a5fa;
        border-radius: 12px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
    }

    .badge {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
    }
</style>

<div class="container mt-4">
    <div class="page-header">
        <h1 class="page-title">Мої проєкти</h1>
    </div>

    @if($projects->isEmpty())
        <div class="alert alert-info">
            У вас поки немає активних проєктів.
        </div>
    @else
        <div class="table-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                    <tr>
                        <th>Назва</th>
                        <th>Статус</th>
                        <th>Оновлено</th>
                        <th class="text-end">Дії</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($projects as $project)
                        <tr>
                            <td><strong>{{ $project->name }}</strong></td>
                            <td>
                                <span class="badge" style="background: rgba(255, 72, 0, 0.2); color: #ff4800;">
                                    {{ $project->status->name ?? '—' }}
                                </span>
                            </td>
                            <td>{{ $project->updated_at->format('d.m.Y H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('client.projects.show', $project) }}" class="btn btn-sm btn-primary">
                                    📂 Відкрити
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
