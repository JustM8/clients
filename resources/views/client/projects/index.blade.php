@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h3>Мої проєкти</h3>

        @if($projects->isEmpty())
            <div class="alert alert-info">У вас поки немає активних проєктів.</div>
        @else
            <table class="table table-bordered align-middle">
                <thead>
                <tr>
                    <th>Назва</th>
                    <th>Статус</th>
                    <th>Оновлено</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($projects as $project)
                    <tr>
                        <td>{{ $project->name }}</td>
                        <td>{{ $project->status->name ?? '—' }}</td>
                        <td>{{ $project->updated_at->format('d.m.Y H:i') }}</td>
                        <td class="text-center">
                            <a href="{{ route('client.projects.show', $project) }}" class="btn btn-sm btn-primary">
                                Відкрити
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
