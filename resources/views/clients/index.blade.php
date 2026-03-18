@extends('layouts.app')

@section('title', 'Clients')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-gray-900">Clients</h1>
    <a href="{{ route('clients.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Add New Client
    </a>
</div>

<div class="bg-white shadow overflow-hidden sm:rounded-md">
    <ul class="divide-y divide-gray-200">
        @forelse($clients as $client)
        <li>
            <a href="{{ route('clients.show', $client) }}" class="block hover:bg-gray-50">
                <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                    <span class="text-white font-medium">{{ substr($client->company_name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $client->company_name }}</div>
                                <div class="text-sm text-gray-500">{{ $client->contact_person }} • {{ $client->email }}</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $client->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $client->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
            </a>
        </li>
        @empty
        <li class="px-4 py-8 text-center text-gray-500">
            No clients found. <a href="{{ route('clients.create') }}" class="text-blue-600 hover:text-blue-500">Create your first client</a>
        </li>
        @endforelse
    </ul>
</div>

<div class="mt-4">
    {{ $clients->links() }}
</div>
@endsection

