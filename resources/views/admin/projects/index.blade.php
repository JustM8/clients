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

    .btn-create {
        background: linear-gradient(135deg, #ff4800 0%, #ff6b00 100%);
        border: none;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(255, 72, 0, 0.3);
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 72, 0, 0.5);
        color: white;
    }

    .table-card {
        background: rgba(42, 42, 42, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 72, 0, 0.2);
        border-radius: 16px;
        padding: 1.5rem;
        overflow: hidden;
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

    .table tbody tr {
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background: rgba(255, 72, 0, 0.05);
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border: none;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: none;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
    }

    .pagination {
        margin-top: 1.5rem;
    }

    .page-link {
        background: rgba(42, 42, 42, 0.6);
        border: 1px solid rgba(255, 72, 0, 0.2);
        color: #ff4800;
        margin: 0 0.25rem;
        border-radius: 8px;
    }

    .page-link:hover {
        background: rgba(255, 72, 0, 0.2);
        border-color: #ff4800;
        color: #ff4800;
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, #ff4800 0%, #ff6b00 100%);
        border-color: #ff4800;
    }
</style>

<div class="container">
    <div class="page-header d-flex justify-content-between align-items-center">
        <h1 class="page-title">{{ __('admin/projects.index.title') }}</h1>
        <a href="{{ route('admin.projects.create') }}" class="btn btn-create">
            ➕ {{ __('admin/projects.index.add_button') }}
        </a>
    </div>

    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>{{ __('admin/projects.index.table.id') }}</th>
                        <th>{{ __('admin/projects.index.table.name') }}</th>
                        <th>{{ __('admin/projects.index.table.client') }}</th>
                        <th>{{ __('admin/projects.index.table.status') }}</th>
                        <th>{{ __('admin/projects.index.table.created_at') }}</th>
                        <th class="text-end">{{ __('admin/projects.index.table.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($projects as $project)
                    <tr>
                        <td><strong>#{{ $project->id }}</strong></td>
                        <td>{{ $project->name }}</td>
                        <td>{{ $project->client?->name ?? '—' }}</td>
                        <td>
                            <span class="badge" style="background: rgba(255, 72, 0, 0.2); color: #ff4800; padding: 0.5rem 1rem; border-radius: 8px;">
                                {{ $project->status?->name ?? '—' }}
                            </span>
                        </td>
                        <td>{{ $project->created_at->format('d.m.Y') }}</td>

                        <td class="text-end">
                            <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-sm btn-primary">
                                ✏️ {{ __('admin/projects.index.table.edit') }}
                            </a>

                            <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('{{ __('admin/projects.index.table.delete_confirm') }}')">
                                    🗑
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $projects->links() }}
        </div>
    </div>
</div>
@endsection