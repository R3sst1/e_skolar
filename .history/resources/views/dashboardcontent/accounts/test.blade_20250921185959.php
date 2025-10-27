<div>
    <h1>Test Page</h1>
    <p>This is a test page to see if Livewire is working.</p>
    <p>Users count: {{ $users->count() }}</p>
    <p>Total users: {{ $users->total() }}</p>
    
    @if($users->count() > 0)
        <ul>
            @foreach($users as $user)
                <li>{{ $user->first_name }} {{ $user->last_name }} ({{ $user->username }})</li>
            @endforeach
        </ul>
    @else
        <p>No users found.</p>
    @endif
</div>
