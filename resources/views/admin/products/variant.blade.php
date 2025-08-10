<div class="p-3 border-bottom border-end-md border-primary flex-grow-1" data-variant-id="{{ $variant->id }}">
    <div class="d-flex justify-content-between mb-2">
        <div class="fw-bold">{!! $variant->translateAttribute('name') !!}</div>
        <div class="text-primary fw-500">
            {{ $variant->prices->first() && $variant->prices->first()->price ? $variant->prices->first()->price->formatted : '' }}
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <span class="badge bg-primary bg-opacity-10 text-white me-2">
                SKU: {{ $variant->sku ?? 'N/A' }}</span>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm edit-variant-btn" 
                    data-variant-id="{{ $variant->id }}"
                    data-variant-name-en="{{ $variant->translateAttribute('name', 'en') }}"
                    data-variant-name-gr="{{ $variant->translateAttribute('name', 'gr') }}"
                    data-variant-price="{{ $variant->prices->first()->price->value }}"
                    data-variant-sku="{{ $variant->sku }}"
                    data-variant-stock="{{ $variant->stock }}">
                <i class="bi bi-pencil"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary delete-variant-btn" 
                    data-url="{{ route('admin.products.variants.destroy', [$product->id, $variant->id]) }}">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>
</div>