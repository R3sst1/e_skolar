@foreach($institutions as $institution)
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
    <td>
        <div class="text-center">
            <div class="font-medium">{{ $institution->total_scholars_count }}</div>
            <div class="text-slate-500 text-xs">{{ $institution->active_scholars_count }} active</div>
        </div>
    </td>
    <td>
        <span class="badge badge-{{ $institution->is_active ? 'success' : 'danger' }}">
            {{ $institution->is_active ? 'Active' : 'Inactive' }}
        </span>
    </td>
    <td>
        <div class="flex items-center gap-2">
            <button class="btn btn-sm btn-outline-primary" onclick="viewInstitution({{ $institution->id }})">
                <i data-lucide="eye" class="w-4 h-4"></i>
            </button>
            <button class="btn btn-sm btn-outline-secondary" onclick="editInstitution({{ $institution->id }})">
                <i data-lucide="edit" class="w-4 h-4"></i>
            </button>
            @if($institution->scholars()->count() === 0)
            <button class="btn btn-sm btn-outline-danger" onclick="deleteInstitution({{ $institution->id }})">
                <i data-lucide="trash" class="w-4 h-4"></i>
            </button>
            @endif
        </div>
    </td>
</tr>
@endforeach 