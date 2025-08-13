@extends('admin.app')

@section('toolbar')
    @include('admin.partials.buttons', ['title' => 'Services', 'asset' => 'Services', 'custom_button' => '<a href="javascript:void(0);" onclick="showCreateModal()" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Create New </a>', 'buttons' => [
        []
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
                        <th>Discount</th>
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
                            
                                @if($product->getMedia('thumbnails')->empty() && $product->getMedia('thumbnails')->first())
                                <img src="{{ $product->getMedia('thumbnails')->first()->getUrl() }}" 
                                     alt="{{ $product->translateAttribute('name') }}" 
                                     class="rounded" 
                                     style="width: 48px; height: 48px; object-fit: cover;">
                                @else
                                <div class="rounded bg-light d-flex align-items-center justify-content-center" 
                                     style="width: 48px; height: 48px;">
                                     <img src=""/>
                                    <i class="bi bi-box-seam text-muted"></i>
                                </div>
                                @endif
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
                            <!-- @todo add discount -->
                            <span class="badge bg-warning bg-opacity-10 text-warning">
                                {{ $product->discount ? $product->discount . '%' : 'No discount' }}
                            </span>
                        </td>
                        <td>
                            {{-- Sales count would need to be implemented based on your orders --}}
                            @if($product->total_sales)
                                {{ $product->total_sales . ' sales' }}
                            @else
                                <span class="small grey text-grey">No sales</span>
                            @endif
                        </td>
                        <td>

                            {{ $product->variants->first()->prices->first()->price->formatted() ?? '-' }} {!! $product->variants->count() > 1 ? '' : '' !!} 
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
<!-- Create Product Modal -->
<div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createProductModalLabel">Create New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.products.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="productTitle" class="form-label">Product Title</label>
                        <input type="text" class="form-control" id="productTitle" name="name" required>
                    </div>
                    <!-- Minimal required fields for LunarPHP -->
                    <!-- <div class="mb-3">
                        <label for="productSlug" class="form-label">Slug</label>
                        <input type="text" class="form-control" id="productSlug" name="slug" required>
                    </div> -->
                    <input type="hidden" name="product_type_id" value="1"/>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    

    // Auto-generate slug from title
    const titleInput = document.getElementById('productTitle');
    const slugInput = document.getElementById('productSlug');
    
    if (titleInput && slugInput) {
        titleInput.addEventListener('input', function() {
            const slug = this.value.toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
            slugInput.value = slug;
        });
    }
});

// Show modal when "New" button is clicked
function showCreateModal() {
    const modal = new bootstrap.Modal(document.getElementById('createProductModal'));
    modal.show();
}
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