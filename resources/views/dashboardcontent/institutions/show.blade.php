@extends('layouts.app')

@section('title', 'Institution Details')

@section('content')
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12">
        <div class="intro-y flex items-center h-10">
         
        </div>
        
        <div class="grid grid-cols-12 gap-6 mt-5">
            <!-- Institution Information -->
            <div class="col-span-12 lg:col-span-4">
                <div class="intro-y box">
                    <div class="flex items-center p-5 border-b border-slate-200/60">
                        <h2 class="font-medium text-base mr-auto">Institution Information</h2>
                        <div class="flex gap-2">
                            <a href="{{ route('institutions.edit', $institution->id) }}" class="btn btn-sm btn-outline-secondary">
                                <i data-lucide="edit" class="w-4 h-4 mr-1"></i>Edit
                            </a>
                            <a href="{{ route('institutions.index') }}" class="btn btn-sm btn-outline-primary">
                                <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>Back
                            </a>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="mb-4">
                            <h3 class="font-medium text-lg">{{ $institution->name }}</h3>
                            <span class="badge badge-outline-{{ $institution->type === 'university' ? 'primary' : ($institution->type === 'college' ? 'success' : 'warning') }} mt-2">
                                {{ ucfirst($institution->type) }}
                            </span>
                        </div>
                        
                        <div class="space-y-3">
                            @if($institution->address)
                            <div>
                                <label class="form-label text-slate-600">Address</label>
                                <p class="text-sm">{{ $institution->address }}</p>
                            </div>
                            @endif
                            
                            @if($institution->contact_person)
                            <div>
                                <label class="form-label text-slate-600">Contact Person</label>
                                <p class="text-sm">{{ $institution->contact_person }}</p>
                            </div>
                            @endif
                            
                            @if($institution->contact_email)
                            <div>
                                <label class="form-label text-slate-600">Contact Email</label>
                                <p class="text-sm">{{ $institution->contact_email }}</p>
                            </div>
                            @endif
                            
                            @if($institution->contact_phone)
                            <div>
                                <label class="form-label text-slate-600">Contact Phone</label>
                                <p class="text-sm">{{ $institution->contact_phone }}</p>
                            </div>
                            @endif
                            
                            @if($institution->description)
                            <div>
                                <label class="form-label text-slate-600">Description</label>
                                <p class="text-sm">{{ $institution->description }}</p>
                            </div>
                            @endif
                            
                            <div>
                                <label class="form-label text-slate-600">Status</label>
                                <span class="badge badge-{{ $institution->is_active ? 'success' : 'danger' }}">
                                    {{ $institution->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Statistics -->
                <div class="intro-y box mt-5">
                    <div class="flex items-center p-5 border-b border-slate-200/60">
                        <h2 class="font-medium text-base mr-auto">Statistics</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-primary">{{ $institution->total_scholars_count }}</div>
                                <div class="text-xs text-slate-500">Total Scholars</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-success">{{ $institution->active_scholars_count }}</div>
                                <div class="text-xs text-slate-500">Active Scholars</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Scholars List -->
            <div class="col-span-12 lg:col-span-8">
                <div class="intro-y box">
                    <div class="flex items-center p-5 border-b border-slate-200/60">
                        <h2 class="font-medium text-base mr-auto">Scholars</h2>
                        <div class="text-slate-500 text-sm">{{ $scholars->total() }} total scholars</div>
                    </div>
                    <div class="p-5">
                        @if($scholars->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="table table-report -mt-2">
                                <thead>
                                    <tr>
                                        <th class="whitespace-nowrap">Scholar Name</th>
                                        <th class="whitespace-nowrap">Course</th>
                                        <th class="whitespace-nowrap">Year Level</th>
                                        <th class="whitespace-nowrap">Status</th>
                                        <th class="whitespace-nowrap">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($scholars as $scholar)
                                    <tr>
                                        <td>
                                            <div class="font-medium">
                                                {{ $scholar->user->first_name }} {{ $scholar->user->last_name }}
                                            </div>
                                            <div class="text-slate-500 text-xs">{{ $scholar->user->email }}</div>
                                        </td>
                                        <td>{{ $scholar->course }}</td>
                                        <td>{{ $scholar->year_level }}</td>
                                        <td>
                                            <span class="badge badge-{{ $scholar->status === 'active' ? 'success' : ($scholar->status === 'graduated' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($scholar->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('view.profile.other', $scholar->user_id) }}" class="btn btn-sm btn-outline-primary">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-5">
                            {{ $scholars->links() }}
                        </div>
                        @else
                        <div class="text-center py-8">
                            <i data-lucide="users" class="w-16 h-16 text-slate-400 mx-auto mb-4"></i>
                            <p class="text-slate-500">No scholars found for this institution.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 