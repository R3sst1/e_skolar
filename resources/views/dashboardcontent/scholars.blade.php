@extends('layouts.app')
@section('title', 'Scholars')
@section('content')
    <!-- BEGIN: Statistics -->
    @if(Auth::user()->isSuperAdmin()|| Auth::user()->isAdmin())
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
            <div class="report-box zoom-in">
                <div class="box p-5">
                    <div class="flex">
                        <i data-lucide="user-check" class="report-box__icon text-primary"></i>
                    </div>
                    <div class="text-3xl font-medium leading-8 mt-6">{{ $stats['total_active'] }}</div>
                    <div class="text-base text-slate-500 mt-1">Active Scholars</div>
                </div>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
            <div class="report-box zoom-in">
                <div class="box p-5">
                    <div class="flex">
                        <i data-lucide="graduation-cap" class="report-box__icon text-pending"></i>
                    </div>
                    <div class="text-3xl font-medium leading-8 mt-6">{{ $stats['total_graduated'] }}</div>
                    <div class="text-base text-slate-500 mt-1">Graduated</div>
                </div>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
            <div class="report-box zoom-in">
                <div class="box p-5">
                    <div class="flex">
                        <i data-lucide="school" class="report-box__icon text-warning"></i>
                    </div>
                    <div class="text-3xl font-medium leading-8 mt-6">{{ $stats['total_institutions'] }}</div>
                    <div class="text-base text-slate-500 mt-1">Institutions</div>
                </div>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
            <div class="report-box zoom-in">
                <div class="box p-5">
                    <div class="flex">
                        <i data-lucide="map-pin" class="report-box__icon text-success"></i>
                    </div>
                    <div class="text-3xl font-medium leading-8 mt-6">{{ $stats['total_barangays'] }}</div>
                    <div class="text-base text-slate-500 mt-1">Barangays</div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- END: Statistics -->

    <div class="col-span-12 mt-8">
        <div class="grid grid-cols-12 gap-6 mt-5">
           <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <select class="form-select box" name="category" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    <option value="Student" {{ request('category') == 'Student' ? 'selected' : '' }}>Student</option>
                    <option value="Master Degree" {{ request('category') == 'Master Degree' ? 'selected' : '' }}>Master Degree</option>
                    <option value="Graduate" {{ request('category') == 'Graduate' ? 'selected' : '' }}>Graduate</option>
                </select>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <select class="form-select box" name="institution" onchange="this.form.submit()">
                    <option value="">All Institutions</option>
                    @foreach($institutions as $institution)
                        <option value="{{ $institution }}" {{ request('institution') == $institution ? 'selected' : '' }}>
                            {{ $institution }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <select class="form-select box" name="barangay" onchange="this.form.submit()">
                    <option value="">All Barangays</option>
                    @foreach($barangays as $barangay)
                        <option value="{{ $barangay }}" {{ request('barangay') == $barangay ? 'selected' : '' }}>
                            {{ $barangay }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <select class="form-select box" name="status" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="graduated" {{ request('status') == 'graduated' ? 'selected' : '' }}>Graduated</option>
                    <option value="discontinued" {{ request('status') == 'discontinued' ? 'selected' : '' }}>Discontinued</option>
                </select>
            </div>

        </div>

            <div class="grid grid-cols-12 gap-6 mt-5">
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

                        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                            <div class="w-56 relative text-slate-500">
                                <input type="text" name="search" class="form-control w-56 box pr-10" placeholder="Search..." value="{{ request('search') }}">
                                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i> 
                            </div>
                        </div>

                        <div class="hidden md:block mx-auto text-slate-500">
                            Showing {{ $scholars->firstItem() ?? 0 }} to {{ $scholars->lastItem() ?? 0 }} of {{ $scholars->total() ?? 0 }} entries
                        </div>
                        
                        <div class="grid grid-cols-12 gap-6 mt-5">
                            </div>
                        
                    </div>
                </div>
             <div class="grid grid-cols-12 gap-6 mt-5">
                </div>
        <!-- BEGIN: Scholars List -->
        <div class="intro-y col-span-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($scholars as $scholar)
                <div class="intro-y">
                    <div class="box">
                        <div class="flex flex-col lg:flex-row items-center p-5">
                            <div class="w-24 h-24 lg:w-12 lg:h-12 image-fit lg:mr-1">
                                <img alt="Scholar photo" class="rounded-full" src="{{ asset('Images/normalpicture.png') }}">
                            </div>
                            <div class="lg:ml-2 lg:mr-auto text-center lg:text-left mt-3 lg:mt-0">
                                <a href="{{ route('view.profile.other', $scholar->user_id) }}" class="font-medium">
                                    {{ $scholar->user->first_name }} {{ $scholar->user->last_name }}
                                </a> 
                                <div class="text-slate-500 text-xs mt-0.5">{{ $scholar->institution }}</div>
                                <div class="text-slate-500 text-xs">{{ $scholar->course }} - {{ $scholar->year_level }}</div>
                            </div>
                            <div class="flex mt-4 lg:mt-0">
                                <span class="px-2 py-1 rounded-full text-xs mr-2 
                                    @if($scholar->status === 'active') bg-success text-white
                                    @elseif($scholar->status === 'graduated') bg-warning text-white
                                    @else bg-danger text-white @endif">
                                    {{ ucfirst($scholar->status) }}
                                </span>
                                <a href="{{ route('view.profile.other', $scholar->user_id) }}" class="btn btn-primary py-1 px-2">View Profile</a>
                                @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                                <form action="{{ route('scholars.drop', $scholar->id) }}" method="POST" class="ml-2" onsubmit="return confirm('Are you sure you want to drop this scholar?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger py-1 px-2">Drop Scholar</button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="intro-y col-span-12 text-center">
                    <div class="box p-5">
                        <p>No scholars found matching your criteria.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
        <!-- END: Scholars List -->

        <!-- BEGIN: Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
            {{ $scholars->links() }}
        </div>
        <!-- END: Pagination -->
    </div>
@endsection 