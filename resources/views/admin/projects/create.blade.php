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

    .btn-success {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        border: none;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
    }

    .btn-secondary {
        background: rgba(107, 114, 128, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.9);
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: rgba(107, 114, 128, 0.5);
        color: white;
    }
</style>

<div class="container">
    <div class="page-header">
        <h1 class="page-title">{{ __('admin/projects.create.title') }}</h1>
    </div>

    <div class="form-card">
        <form action="{{ route('admin.projects.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">{{ __('admin/projects.create.fields.name') }}</label>
                <input type="text" name="name" class="form-control" required placeholder="Enter project name">
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('admin/projects.create.fields.description') }}</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Project description..."></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('admin/projects.create.fields.type') }}</label>
                    <select name="type_id" class="form-select">
                        <option value="">— Select Type —</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}">
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('admin/projects.create.fields.client') }}</label>
                    <select name="client_id" class="form-select">
                        <option value="">— Select Client —</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">
                                {{ $client->name }} @if($client->company_name) - {{ $client->company_name }}@endif
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('admin/projects.create.fields.status') }}</label>
                    <select name="status_id" class="form-select">
                        <option value="">— Select Status —</option>
                        @foreach($allStages as $status)
                            <option value="{{ $status->id }}">
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('admin/projects.create.fields.rate') }}</label>
                    <input type="number" name="rate" class="form-control" step="0.01" min="0" placeholder="0.00">
                </div>
            </div>

            <div class="d-flex gap-3 mt-4">
                <button class="btn btn-success">
                    ✅ {{ __('admin/projects.create.buttons.save') }}
                </button>

                <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">
                    ← {{ __('admin/projects.create.buttons.back') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection