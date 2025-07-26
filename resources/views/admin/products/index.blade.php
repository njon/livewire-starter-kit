@extends('admin.app')

@section('toolbar')
    @include('admin.partials.buttons', ['title' => 'Services', 'buttons' => [
        ['new' => true]
    ]])
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="card-header bg-transparent border-bottom py-3">
            <h3 class="h5 mb-0 d-flex align-items-center">
                <input type="checkbox" id="selectAll" class="form-check-input"> <label for="selectAll" class="form-check-label ms-3 fs-14">Select all items</label>
            </h3>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="productsTable">
                <thead class="table-light">
                    <tr>
                        <th width="40" class="ps-4">
                        </th>
                        <th>Status</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Sales</th>
                        <th>Price</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="ps-4">
                            <input type="checkbox" name="selected_products[]" value="{{ $product->id }}" class="form-check-input product-checkbox">
                        </td>
                        <td>
                            <span class="badge bg-{{ $product->status === 'published' ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $product->status === 'published' ? 'success' : 'secondary' }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded bg-light" style="width: 48px; height: 48px;"></div>
                                <div>
                                    <h6 class="mb-0">
                                        <a class="text-muted text-decoration-none" href="{{ route('admin.products.edit', $product->id) }}">{{ $product->translateAttribute('name') }}</a>
                                    </h6>
                                    @if($product->variants->count() > 1)
                                        <small class="text-muted">{{ $product->variants->count() }} variants</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($product->collections->isNotEmpty())
                                {{ $product->collections->first()->translateAttribute('name') }}
                                @if($product->collections->count() > 1)
                                    <small class="text-muted">+{{ $product->collections->count() - 1 }} more</small>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            {{-- Sales count would need to be implemented based on your orders --}}
                            {{ $product->sales_count ?? 0 }} sold
                        </td>
                        <td>
                            @if($product->variants->first())
                                {{ $product->variants->first()->price }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-gear"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.products.edit', $product->id) }}">
                                            <i class="bi bi-pencil me-2"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline">
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
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-box-seam me-2"></i> No products found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkboxes
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.product-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    
    // Uncheck "select all" if any product checkbox is unchecked
    const productCheckboxes = document.querySelectorAll('.product-checkbox');
    productCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (!this.checked) {
                document.getElementById('selectAll').checked = false;
            }
        });
    });
});
</script>
@endsection