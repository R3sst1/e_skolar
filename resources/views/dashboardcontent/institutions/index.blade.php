@extends('layouts.app')
@section('title', 'Institution Management')
@section('content')
<div class="container mx-auto px-4">
    
    
    <div class="flex justify-between items-center mb-6">
       
        @if(Auth::user()->isSuperAdmin())
        <a href="{{ route('institutions.create') }}" class="btn btn-primary">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            Add Institution
        </a>
        @endif
    </div>
    
    <div class="overflow-x-auto">
        <table class="table table-report">
            <thead>
                <tr>
                    <th>Institution Name</th>
                    <th>Type</th>
                    <th>Contact Person</th>
                    <th>Contact Email</th>
                    <th>Contact Phone</th>
                    <th>Address</th>
                    <th class="text-center">Scholars</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($institutions as $institution)
                <tr>
                    <td>
                        <div class="font-medium">{{ $institution->name }}</div>
                        @if($institution->address)
                            <div class="text-slate-500 text-xs">{{ $institution->address }}</div>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-outline-{{ $institution->type === 'university' ? 'primary' : ($institution->type === 'college' ? 'success' : 'warning') }}">
                            {{ ucfirst($institution->type) }}
                        </span>
                    </td>
                    <td>{{ $institution->contact_person ?? 'N/A' }}</td>
                    <td>{{ $institution->contact_email ?? 'N/A' }}</td>
                    <td>{{ $institution->contact_phone ?? 'N/A' }}</td>
                    <td>{{ $institution->address ?? 'N/A' }}</td>
                    <td class="text-center">
                        <div class="text-center">
                            <div class="font-medium">{{ $institution->total_scholars_count }}</div>
                            <div class="text-slate-500 text-xs">{{ $institution->active_scholars_count }} active</div>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-{{ $institution->is_active ? 'success' : 'danger' }}">
                            {{ $institution->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="flex items-center gap-2 justify-center">
                            <a href="{{ route('institutions.show', $institution) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            @if(Auth::user()->isSuperAdmin())
                            <a href="{{ route('institutions.edit', $institution) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('institutions.destroy', $institution) }}" method="POST" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this institution?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i data-lucide="trash" class="w-4 h-4"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-slate-500 py-8">No institutions found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection 