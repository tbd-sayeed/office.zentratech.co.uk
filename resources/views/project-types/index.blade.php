@extends('layouts.app')

@section('title', 'Project Types')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 fw-bold text-dark">Project Types</h1>
    <a href="{{ route('project-types.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-1" viewBox="0 0 16 16">
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>
        Add Project Type
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Sort Order</th>
                        <th>Status</th>
                        <th width="120"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projectTypes as $pt)
                    <tr>
                        <td class="fw-semibold">{{ $pt->name }}</td>
                        <td>{{ $pt->sort_order }}</td>
                        <td><span class="badge {{ $pt->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $pt->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td>
                            <a href="{{ route('project-types.edit', $pt) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="{{ route('project-types.destroy', $pt) }}" class="d-inline" onsubmit="return confirm('Delete this project type?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-4 text-muted">No project types yet. <a href="{{ route('project-types.create') }}">Add one</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">{{ $projectTypes->links() }}</div>
@endsection
