@extends('layouts.app')
@section('title', 'Barangay Management')
@section('content')
<div class="container mx-auto px-4">
    
    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif
    @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
    <div class="mb-6">
        <form action="{{ route('barangays.store') }}" method="POST" class="flex flex-col md:flex-row gap-2 items-center">
            @csrf
            <input type="text" name="name" class="form-control w-full md:w-64" placeholder="Add new barangay..." required>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="funded" class="form-check-input"> Funded
            </label>
            <button type="submit" class="btn btn-primary">Add Barangay</button>
        </form>
    </div>
    @endif
    <div class="overflow-x-auto">
        <table class="table table-report">
            <thead>
                <tr>
                    <th>Barangay Name</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangays as $barangay)
                <tr>
                    <td>
                        @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                        <form action="{{ route('barangays.update', $barangay) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            @method('PUT')
                            <input type="text" name="name" value="{{ $barangay->name }}" class="form-control w-48" required>
                            <label class="flex items-center gap-1">
                                <input type="checkbox" name="funded" class="form-check-input" {{ $barangay->funded ? 'checked' : '' }}>
                                Funded
                            </label>
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </form>
                        @else
                        {{ $barangay->name }}
                        @endif
                    </td>
                    <td class="text-center">
                        @if($barangay->funded)
                            <span class="badge bg-success">Funded</span>
                        @else
                            <span class="badge bg-warning">Pending</span>
                        @endif
                    </td>
                    <td class="text-center flex gap-2 justify-center">
                        @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                        <form action="{{ route('barangays.toggleFunded', $barangay) }}" method="POST" style="display:inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $barangay->funded ? 'btn-warning' : 'btn-success' }}">
                                {{ $barangay->funded ? 'Mark as Pending' : 'Mark as Funded' }}
                            </button>
                        </form>
                        <form action="{{ route('barangays.destroy', $barangay) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this barangay?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                        @else
                        <span class="text-slate-400">N/A</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center text-slate-500 py-8">No barangays found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection 