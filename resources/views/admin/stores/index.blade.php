@extends('admin.app')

@section('toolbar')
    @include('admin.partials.buttons', [
        'title' => 'Stores', 
        'asset' => 'Stores', 
        'buttons' => [['new' => true]]
    ])
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="card-header bg-transparent border-bottom py-3">
            <h3 class="h5 mb-0 d-flex align-items-center">
                <input type="checkbox" id="selectAll" class="form-check-input"> 
                <label for="selectAll" class="form-check-label ms-3 fs-14">Select all stores</label>
            </h3>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="storesTable">
                <thead class="table-light">
                    <tr>
                        <th width="40" class="ps-4"></th>
                        <th>Status</th>
                        <th>Store Details</th>
                        <th>Contact</th>
                        <th>Location</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stores as $store)
                    <tr>
                        <td class="ps-4">
                            <input type="checkbox" name="selected_stores[]" value="{{ $store->id }}" class="form-check-input store-checkbox">
                        </td>
                        <td>
                            <span class="badge bg-{{ $store->status == 'active' ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $store->status == 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($store->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($store->logo)
                                <img src="{{ asset('storage/'.$store->logo) }}" alt="{{ $store->name }}" class="rounded" style="width: 48px; height: 48px; object-fit: cover;">
                                @else
                                <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <i class="bi bi-shop text-secondary"></i>
                                </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">
                                        <a class="text-muted text-decoration-none" href="{{ route('stores.edit', $store->id) }}">
                                            {{ $store->name }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        {{ $store->description ? Str::limit(strip_tags($store->description), 50) : 'No description' }}
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small">
                                <div><i class="bi bi-telephone me-2"></i> {{ $store->phone ?? 'N/A' }}</div>
                                <div><i class="bi bi-envelope me-2"></i> {{ $store->email ?? 'N/A' }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="small">
                                <div><i class="bi bi-geo-alt me-2"></i> {{ $store->address ?? 'N/A' }}</div>
                                @if($store->website)
                                <div><i class="bi bi-globe me-2"></i> <a href="{{ $store->website }}" target="_blank">Website</a></div>
                                @endif
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-gear"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('stores.edit', $store->id) }}">
                                            <i class="bi bi-pencil me-2"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('stores.edit', $store->id) }}">
                                            <i class="bi bi-eye me-2"></i> View
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('stores.destroy', $store->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger"
                                                onclick="return confirm('Are you sure?')">
                                                <i class="bi bi-trash me-2"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-shop me-2"></i> No stores found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($stores->hasPages())
        <div class="card-footer bg-transparent">
            {{ $stores->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkboxes
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.store-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    
    // Uncheck "select all" if any store checkbox is unchecked
    const storeCheckboxes = document.querySelectorAll('.store-checkbox');
    storeCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (!this.checked) {
                document.getElementById('selectAll').checked = false;
            }
        });
    });
});
</script>
@endsection