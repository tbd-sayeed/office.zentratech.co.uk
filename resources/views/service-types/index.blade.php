@extends('layouts.app')

@section('title', 'Service Types')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 fw-bold text-dark">Service Types</h1>
    <a href="{{ route('service-types.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-1" viewBox="0 0 16 16">
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>
        Add Service Type
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Form Section</th>
                        <th>Sort Order</th>
                        <th>Status</th>
                        <th width="120"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($serviceTypes as $st)
                    <tr>
                        <td class="fw-semibold">{{ $st->name }}</td>
                        <td><span class="badge bg-secondary">{{ str_replace('_', ' ', ucfirst($st->form_section)) }}</span></td>
                        <td>{{ $st->sort_order }}</td>
                        <td><span class="badge {{ $st->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $st->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td>
                            <a href="{{ route('service-types.edit', $st) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="{{ route('service-types.destroy', $st) }}" class="d-inline" onsubmit="return confirm('Delete this service type?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted">No service types yet. <a href="{{ route('service-types.create') }}">Add one</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">{{ $serviceTypes->links() }}</div>
@endsection
