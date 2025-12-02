@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Проєкти</h3>
        <a href="{{ route('admin.projects.create') }}" class="btn btn-success mb-3">Додати проєкт</a>

        <table class="table table-bordered align-middle">
            <thead>
            <tr>
                <th>ID</th>
                <th>Назва</th>
                <th>Клієнт</th>
                <th>Статус</th>
                <th>Створено</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($projects as $project)
                <tr>
                    <td>{{ $project->id }}</td>
                    <td>{{ $project->name }}</td>
                    <td>{{ $project->client?->name ?? '—' }}</td>
                    <td>{{ $project->status?->name ?? '—' }}</td>
                    <td>{{ $project->created_at->format('d.m.Y') }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-sm btn-primary">Редагувати</a>
                        <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Видалити проєкт?')">🗑</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $projects->links() }}
    </div>
@endsection
