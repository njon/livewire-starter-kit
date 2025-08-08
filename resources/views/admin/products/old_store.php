<div class="col-lg-6">
            <div class="card mb-4 border-0 shadow-sm">
                <div
                    class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h3 class="h5 mb-0 d-flex align-items-center">
                        <i class="bi bi-shop me-2 text-primary"></i> Store Availability
                    </h3>
                    <button class="btn btn-sm " data-bs-toggle="modal" data-bs-target="#storeModal" type="button">
                        <i class="bi bi-plus-circle me-1"></i> Add Store
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="storesTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Store</th>
                                    <th class="text-center">Status</th>
                                    <th>Availability Period</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($product->channels->count() > 0)
                                @foreach($product->channels as $channel)
                                @php
                                $pivot = $channel->pivot;
                                @endphp
                                <tr>
                                    <td class="ps-4 fw-medium">{{ $channel->name }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge {{ $pivot->enabled ? 'bg-success bg-opacity-10 text-success' : 'bg-secondary bg-opacity-10 text-secondary' }}">
                                            <i
                                                class="bi {{ $pivot->enabled ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }} me-1"></i>
                                            {{ $pivot->enabled ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            @if ($pivot->ends_at == '' && $pivot->starts_at == '')
                                            Always available
                                            @else
                                            {{ $pivot->starts_at ? $pivot->starts_at : 'Not set' }} -
                                            {{ $pivot->ends_at ? $pivot->ends_at : 'Not set' }}
                                            @endif
                                        </small>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a class="dropdown-item text-danger remove-channel" href="#"
                                            data-channel-id="{{ $channel->id }}">
                                            <i class="bi bi-trash me-2"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>

                        <div class="text-center text-muted py-5" id="no-stores"
                            style="display: {{ $product->channels->isEmpty() ? 'block' : 'none' }}">
                            <i class="bi bi-shop me-2"></i> Please choose stores where this product will be available.
                        </div>


                        <div id="selectedChannelsContainer">
                            @foreach($product->channels as $channel)
                            @php
                            $pivot = $channel->pivot;
                            @endphp
                            <input type="hidden" name="channels[{{ $channel->id }}][id]" value="{{ $channel->id }}">
                            <input type="hidden" name="channels[{{ $channel->id }}][name]" value="{{ $channel->name }}">
                            <input type="hidden" name="channels[{{ $channel->id }}][start_date]"
                                value="{{ $pivot->starts_at ? $pivot->starts_at : '' }}">
                            <input type="hidden" name="channels[{{ $channel->id }}][end_date]"
                                value="{{ $pivot->ends_at ? $pivot->ends_at : '' }}">
                            <input type="hidden" name="channels[{{ $channel->id }}][enabled]"
                                value="{{ $pivot->enabled ? 1 : 0 }}">
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>    
        
        











        VARIANTS

        <!-- Variants Section -->
        <div class="col-lg-6">
            <div class="card mb-4 border-0 shadow-sm">
                <div
                    class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h3 class="h5 mb-0 d-flex align-items-center">
                        <i class="bi bi-collection me-2 text-primary"></i> Product variants
                    </h3>
                    <button class="btn btn-sm btn-outline-primary" id="addVariantBtn" type="button">
                        <i class="bi bi-plus-circle me-1"></i> Add option
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0" id="variantsTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Title</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $variants = $product->variants->skip(1); // Skip the default variant
                                @endphp


                                @foreach($variants as $index => $variant)
                                <tr>
                                    <td class="ps-4">
                                        <input type="hidden" name="variants[{{ $index }}][id]"
                                            value="{{ $variant->id }}">
                                        <input type="text" class="form-control form-control"
                                            name="variants[{{ $index }}][name][en]" placeholder="Product option"
                                            value="{{ old("variants.$index.name.en", $variant->translateAttribute('name', 'en')) }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control"
                                            name="variants[{{ $index }}][sku]" placeholder="SKU"
                                            value="{{ old("variants.$index.sku", $variant->sku) }}">
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">€</span>
                                            <input type="number" class="form-control"
                                                name="variants[{{ $index }}][price]" placeholder="0.00" step="0.01"
                                                value="{{ old("variants.$index.price", $variant->prices->first()->price ?? '') }}">
                                        </div>
                                    </td>

                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-outline-danger remove-variant" type="button">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="text-center text-muted py-5" id="no-variants"
                            style="display: {{ $variants->isEmpty() ? 'block' : 'none' }}">
                            <i class="bi bi-box-seam me-2"></i> No variants available
                        </div>
                    </div>
                </div>
            </div>
        </div>