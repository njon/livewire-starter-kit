<div class="modal fade" id="variantModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Add Variant') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="variantForm" 
                      data-variant-id="{{ $variant->id }}"  
                      data-action="update"
                      data-store-url="{{ route('admin.products.variants.store', $product) }}"
                      data-update-url="{{ route('admin.products.variants.update', [$product, '__variant__']) }}">
                    @csrf
                    @method('POST')
                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <label class="form-label">{{ __('Title (English)') }}</label>
                            <input type="text" class="form-control" name="name[en]" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('Title (Greek)') }}</label>
                            <input type="text" class="form-control" name="name[gr]">
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('Price (€)') }}</label>
                            <input type="number" class="form-control" name="price" required>
                        </div>
                        <!-- <div class="col-12">
                            <label class="form-label">{{ __('SKU') }}</label>
                            <input type="text" class="form-control" name="sku">
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('Stock') }}</label>
                            <input type="number" class="form-control" name="stock" value="0" min="0">
                        </div> -->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary">{{ __('Add Variant') }}</button>
            </div>
        </div>
    </div>
</div>