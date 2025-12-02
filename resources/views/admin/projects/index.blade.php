@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>{{ __('admin/projects.index.title') }}</h3>

        <a href="{{ route('admin.projects.create') }}" class="btn btn-success mb-3">
            {{ __('admin/projects.index.add_button') }}
        </a>

        <table class="table table-bordered align-middle">
            <thead>
            <tr>
                <th>{{ __('admin/projects.index.table.id') }}</th>
                <th>{{ __('admin/projects.index.table.name') }}</th>
                <th>{{ __('admin/projects.index.table.client') }}</th>
                <th>{{ __('admin/projects.index.table.status') }}</th>
                <th>{{ __('admin/projects.index.table.created_at') }}</th>
                <th>{{ __('admin/projects.index.table.actions') }}</th>
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
                        <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-sm btn-primary">
                            {{ __('admin/projects.index.table.edit') }}
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

        {{ $projects->links() }}
    </div>
@endsection

