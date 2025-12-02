@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h3>Створити проєкт</h3>
        <form action="{{ route('admin.projects.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Назва</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Опис</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label>Тип проєкту</label>
                <select name="type_id" class="form-select">
                    <option value="">—</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" @selected(isset($project) && $project->type_id == $type->id)>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Клієнт</label>
                <select name="client_id" class="form-select">
                    <option value="">—</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }} - {{ $client->company_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Статус</label>
                <select name="status_id" class="form-select">
                    <option value="">—</option>
                    @foreach($allStages as $status)
                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Рейт (₴/год)</label>
                <input type="number" name="rate" class="form-control" step="0.01" min="0">
            </div>

            <button class="btn btn-success">Зберегти</button>
            <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Назад</a>
        </form>
    </div>
@endsection
