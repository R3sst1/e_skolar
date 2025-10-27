<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @foreach($users as $user)
    <div class="intro-y" data-user-id="{{ $user->id }}">
        <div class="box">
            <div class="flex flex-col lg:flex-row items-center p-5">
                <div class="w-24 h-24 lg:w-12 lg:h-12 image-fit lg:mr-1">
                    <img alt="User avatar" class="rounded-full" src="{{ asset('Images/normalpicture.png') }}">
                </div>
                <div class="lg:ml-2 lg:mr-auto text-center lg:text-left mt-3 lg:mt-0">
                    <a href="{{ route('view.profile.other', $user->id) }}" class="font-medium">{{ $user->first_name }} {{ $user->last_name }}</a> 
                    <div class="text-slate-500 text-xs mt-0.5">{{ $user->role }}</div>
                </div>
                <div class="flex mt-4 lg:mt-0">
                    @if(Auth::user()->isSuperAdmin())
                        @if($user->role === 'applicant')
                            <button class="btn btn-primary py-1 px-2 mr-2" onclick="updateUserRole('{{ $user->id }}', 'promote')">Make Admin</button>
                        @elseif($user->role === 'admin')
                            <button class="btn btn-danger py-1 px-2 mr-2" onclick="updateUserRole('{{ $user->id }}', 'demote')">Remove Admin</button>
                        @endif
                    @endif
                    <a href="{{ route('view.profile.other', $user->id) }}" class="btn btn-outline-secondary py-1 px-2">View Profile</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@if($users->isEmpty())
    <div class="text-center text-gray-500 py-4">
        No users found matching your search.
    </div>
@endif 