@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <h3>{{ __('admin/projects.create.title') }}</h3>

        <form action="{{ route('admin.projects.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>{{ __('admin/projects.create.fields.name') }}</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>{{ __('admin/projects.create.fields.description') }}</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label>{{ __('admin/projects.create.fields.type') }}</label>
                <select name="type_id" class="form-select">
                    <option value="">—</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}">
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>{{ __('admin/projects.create.fields.client') }}</label>
                <select name="client_id" class="form-select">
                    <option value="">—</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">
                            {{ $client->name }} - {{ $client->company_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>{{ __('admin/projects.create.fields.status') }}</label>
                <select name="status_id" class="form-select">
                    <option value="">—</option>
                    @foreach($allStages as $status)
                        <option value="{{ $status->id }}">
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>{{ __('admin/projects.create.fields.rate') }}</label>
                <input type="number" name="rate" class="form-control" step="0.01" min="0">
            </div>

            <button class="btn btn-success">
                {{ __('admin/projects.create.buttons.save') }}
            </button>

            <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">
                {{ __('admin/projects.create.buttons.back') }}
            </a>

        </form>
    </div>
@endsection

