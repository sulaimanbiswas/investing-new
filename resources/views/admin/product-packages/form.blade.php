@csrf
@push('styles')
    <link href="{{ asset('admin/vendor/select2/css/select2.min.css') }}" rel="stylesheet" />
@endpush
<div class="row">
    <div class="col-md-3 mb-3">
        <label class="form-label">Order Set <span class="text-danger">*</span></label>
        <select name="order_set_id" id="order_set_id" class="form-control" required>
            <option value="">Select Order Set</option>
            @foreach($orderSets as $orderSet)
                <option value="{{ $orderSet->id }}" data-platform-id="{{ $orderSet->platform_id }}" {{ (string) old('order_set_id', $productPackage->order_set_id ?? $preSelectedOrderSetId ?? '') === (string) $orderSet->id ? 'selected' : '' }}>
                    {{ $orderSet->name }}
                </option>
            @endforeach
        </select>
        @error('order_set_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Type <span class="text-danger">*</span></label>
        <select name="type" id="type" class="form-control" required>
            <option value="">Select Type</option>
            <option value="single" {{ old('type', $productPackage->type ?? '') === 'single' ? 'selected' : '' }}>Single</option>
            <option value="combo" {{ old('type', $productPackage->type ?? '') === 'combo' ? 'selected' : '' }}>Combo</option>
        </select>
        @error('type')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Profit % <span class="text-danger">*</span></label>
        <input type="number" name="profit_percentage" id="profit_percentage" class="form-control"
            value="{{ old('profit_percentage', $productPackage->profit_percentage ?? '') }}" min="0" max="100" step="0.01"
            required>
        @error('profit_percentage')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-md-3 mb-3">
        <label class="form-label">Platform <span class="text-danger">*</span></label>
        <select name="platform_id" id="platform_id" class="form-control" required>
            <option value="">Select Platform</option>
            @php
                $selectedPlatformId = old('platform_id', $productPackage->platform_id ?? request('platform_id', ''));
            @endphp
            @foreach($platforms as $platform)
                <option value="{{ $platform->id }}" {{ (string) $selectedPlatformId === (string) $platform->id ? 'selected' : '' }}>
                    {{ $platform->name }}
                </option>
            @endforeach
        </select>
        @error('platform_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>


    {{-- <div class="col-md-3 mb-3">
        <label class="form-label">Platform <span class="text-danger">*</span></label>
        <select name="platform_id" id="platform_id" class="form-control" required>
            <option value="">Select Platform</option>
            @foreach($platforms as $platform)
                <option value="{{ $platform->id }}" {{ (string) old('platform_id', $productPackage->platform_id ?? '') === (string) $platform->id ? 'selected' : '' }}>
                    {{ $platform->name }}
                </option>
            @endforeach
        </select>
        @error('platform_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div> --}}
</div>

@if(isset($productPackage))
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="is_active" class="form-control">
            <option value="1" {{ old('is_active', $productPackage->is_active) ? 'selected' : '' }}>Active</option>
            <option value="0" {{ !old('is_active', $productPackage->is_active) ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
@endif

<div class="mb-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <label class="form-label mb-0">Products <span class="text-danger">*</span></label>
        <button type="button" class="btn btn-sm btn-primary" id="addProductRow">
            <i class="fas fa-plus"></i> Add Product
        </button>
    </div>

    <div id="productsContainer">
        @if(isset($productPackage) && $productPackage->productPackageItems->count() > 0)
            @foreach($productPackage->productPackageItems as $index => $item)
                <div class="product-row card mb-2 p-3" data-index="{{ $index }}">
                    <div class="row align-items-end">
                        <div class="col-md-5">
                            <label class="form-label">Product</label>
                            <select name="products[{{ $index }}][product_id]" class="form-control product-select" required>
                                <option value="">Select Product</option>
                                <option value="{{ $item->product_id }}" selected>{{ $item->product->name ?? 'Selected Product' }}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="products[{{ $index }}][quantity]" class="form-control quantity-input"
                                value="{{ $item->quantity }}" min="1" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Price</label>
                            <input type="number" name="products[{{ $index }}][price]" class="form-control price-input"
                                value="{{ $item->price }}" min="0" step="0.01" required readonly>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm remove-product w-100">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<div class="card bg-light p-3 mb-3">
    <h5 class="mb-3">Package Summary</h5>
    <div class="row">
        <div class="col-md-3">
            <strong>Subtotal:</strong>
            <div class="fs-4 text-primary" id="subtotal">$0.00</div>
        </div>
        <div class="col-md-3">
            <strong>Profit Amount:</strong>
            <div class="fs-4 text-success" id="profitAmount">$0.00</div>
        </div>
        <div class="col-md-3">
            <strong>Total with Profit:</strong>
            <div class="fs-4 text-info" id="totalWithProfit">$0.00</div>
        </div>
        <div class="col-md-3">
            <strong>Expected Income:</strong>
            <div class="fs-4 text-warning" id="expectedIncome">$0.00</div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('admin/vendor/select2/js/select2.full.min.js') }}"></script>
    <script>
        let productIndex = {{ isset($productPackage) && $productPackage->productPackageItems->count() > 0 ? $productPackage->productPackageItems->count() : 1 }};
        
        @php
            $existingProductsData = isset($productPackage) ? $productPackage->productPackageItems->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'price' => $item->price,
                    'quantity' => $item->quantity
                ];
            })->toArray() : [];
        @endphp
        const existingProducts = @json($existingProductsData);

        // Initialize Select2 on product selects with AJAX
        function initializeProductSelect2() {
            $('.product-select').each(function() {
                const $select = $(this);
                
                // Skip if already initialized
                if ($select.data('select2')) {
                    return;
                }
                
                const platformId = $('#platform_id').val();
                if (!platformId) {
                    return;
                }
                
                // Get currently selected product IDs to exclude
                const selectedProductIds = [];
                $('.product-select').each(function() {
                    const val = $(this).val();
                    if (val && val !== $select.val()) {
                        selectedProductIds.push(val);
                    }
                });
                
                // Initialize Select2 with AJAX
                $select.select2({
                    placeholder: 'Search by name or price (e.g., "Product Name" or "$20")',
                    allowClear: true,
                    width: '100%',
                    minimumInputLength: 0,
                    dropdownAutoWidth: false,
                    ajax: {
                        url: '{{ route('admin.product-packages.products') }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                platform_id: platformId,
                                search: params.term || '',
                                page: params.page || 1,
                                exclude: selectedProductIds
                            };
                        },
                        processResults: function(data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.items,
                                pagination: {
                                    more: data.has_more
                                }
                            };
                        },
                        cache: false
                    },
                    templateResult: function(item) {
                        if (item.loading) return item.text;
                        return $('<span>' + item.text + '</span>');
                    }
                }).on('select2:select', function(e) {
                    handleProductSelect($(this), e.params.data);
                });
            });
        }

        function handleProductSelect($select, selectedData) {
            const row = $select.closest('.product-row');
            const priceInput = row.find('.price-input');
            
            console.log('Product selected:', selectedData);
            
            if (selectedData && selectedData.price) {
                priceInput.val(selectedData.price);
                console.log('Price set to:', selectedData.price);
            } else {
                priceInput.val(0);
            }
            
            calculateTotals();

            // Refresh other Select2 instances to update excluded products
            setTimeout(() => {
                reinitializeOtherSelects($select);
            }, 100);
        }

        function reinitializeOtherSelects($currentSelect) {
            const platformId = $('#platform_id').val();
            if (!platformId) return;
            
            const selectedProductIds = [];
            $('.product-select').each(function() {
                const val = $(this).val();
                if (val) {
                    selectedProductIds.push(val);
                }
            });

            $('.product-select').each(function() {
                const $select = $(this);
                
                // Skip current select and already initialized ones
                if ($select.is($currentSelect) || !$select.data('select2')) {
                    return;
                }
                
                // Update exclude list
                $select.data('select2').options.options.ajax.data = function(params) {
                    return {
                        platform_id: platformId,
                        search: params.term || '',
                        page: params.page || 1,
                        exclude: selectedProductIds.filter(id => id !== $select.val())
                    };
                };
            });
        }

        // Auto-select platform when order set changes
        $('#order_set_id').on('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const platformId = selectedOption.dataset.platformId;

            if (platformId) {
                $('#platform_id').val(platformId).trigger('change');
            }
        });

        // Handle type change for single/combo restriction
        $('#type').on('change', function() {
            const type = this.value;
            const $addButton = $('#addProductRow');
            const productRows = document.querySelectorAll('.product-row');

            if (type === 'single') {
                if (productRows.length >= 1) {
                    $addButton.hide();
                } else {
                    $addButton.show();
                }
                // Remove extra rows if more than 1
                Array.from(productRows).slice(1).forEach(row => row.remove());
            } else {
                $addButton.show();
            }
        });

        // Fetch products when platform changes
        $('#platform_id').on('change', function() {
            const platformId = this.value;
            
            // Destroy all Select2 instances
            $('.product-select').each(function() {
                if ($(this).data('select2')) {
                    $(this).select2('destroy');
                }
            });

            if (!platformId) {
                return;
            }

            // Reinitialize Select2 with new platform
            setTimeout(() => {
                initializeProductSelect2();
            }, 100);
        });

        // Add product row
        $('#addProductRow').on('click', function() {
            const type = $('#type').val();
            const currentProductCount = document.querySelectorAll('.product-row').length;

            if (type === 'single' && currentProductCount >= 1) {
                alert('Single type product packages can only have one product');
                return;
            }

            const container = document.getElementById('productsContainer');
            const newRow = document.createElement('div');
            newRow.className = 'product-row card mb-2 p-3';
            newRow.dataset.index = productIndex;

            newRow.innerHTML = `
                <div class="row align-items-end">
                    <div class="col-md-5">
                        <label class="form-label">Product</label>
                        <select name="products[${productIndex}][product_id]" class="form-control product-select" required>
                            <option value="">Select Product</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="products[${productIndex}][quantity]" class="form-control quantity-input" 
                            value="1" min="1" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Price</label>
                        <input type="number" name="products[${productIndex}][price]" class="form-control price-input" 
                            value="0" min="0" step="0.01" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-product w-100">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            `;

            container.appendChild(newRow);
            productIndex++;

            if (type === 'single') {
                $('#addProductRow').hide();
            }

            // Attach events to new row
            attachRowEvents(newRow);

            // Initialize Select2 on the new row's select
            setTimeout(() => {
                initializeProductSelect2();
            }, 150);
        });

        // Remove product row
        $(document).on('click', '.remove-product', function() {
            const row = $(this).closest('.product-row');
            const type = $('#type').val();
            const productRows = document.querySelectorAll('.product-row');

            row.remove();
            calculateTotals();

            // Refresh Select2
            setTimeout(() => {
                initializeProductSelect2();
            }, 100);

            if (type === 'single' && document.querySelectorAll('.product-row').length === 0) {
                $('#addProductRow').show();
            }
        });

        // Calculate totals on quantity/price change
        $(document).on('input', '.quantity-input, .price-input', function() {
            calculateTotals();
        });

        $(document).on('change', '#profit_percentage', function() {
            calculateTotals();
        });

        function attachRowEvents(row) {
            // Events are delegated via document listeners, so we don't need to attach them individually
        }

        function calculateTotals() {
            let subtotal = 0;

            document.querySelectorAll('.product-row').forEach(row => {
                const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                subtotal += quantity * price;
            });

            const profitPercentage = parseFloat(document.getElementById('profit_percentage').value) || 0;
            const profitAmount = (subtotal * profitPercentage) / 100;
            const totalWithProfit = subtotal + profitAmount;

            $('#subtotal').text('$' + subtotal.toFixed(2));
            $('#profitAmount').text('$' + profitAmount.toFixed(2));
            $('#totalWithProfit').text('$' + totalWithProfit.toFixed(2));
            $('#expectedIncome').text('$' + totalWithProfit.toFixed(2));
        }

        // Initialize on page load
        $(document).ready(function() {
            // Reset product index if no products exist
            if (document.querySelectorAll('.product-row').length === 0) {
                productIndex = 0;
            }

            // Check if order set is already selected and set platform
            const orderSetSelect = document.getElementById('order_set_id');
            if (orderSetSelect.value) {
                const selectedOption = orderSetSelect.options[orderSetSelect.selectedIndex];
                const preselectedPlatformId = selectedOption.getAttribute('data-platform-id');

                if (preselectedPlatformId) {
                    $('#platform_id').val(preselectedPlatformId);
                }
            }

            // Initialize Select2
            const platformId = $('#platform_id').val();
            if (platformId) {
                setTimeout(() => {
                    initializeProductSelect2();
                }, 200);
            }

            // Check type and hide/show add button
            const type = $('#type').val();
            const productRows = document.querySelectorAll('.product-row');

            if (type === 'single' && productRows.length >= 1) {
                $('#addProductRow').hide();
            }

            // Load existing product prices if in edit mode
            if (existingProducts.length > 0) {
                setTimeout(() => {
                    document.querySelectorAll('.product-row').forEach((row, index) => {
                        if (existingProducts[index]) {
                            const priceInput = row.querySelector('.price-input');
                            priceInput.value = existingProducts[index].price;
                        }
                    });
                    calculateTotals();
                }, 400);
            } else {
                calculateTotals();
            }
        });
    </script>
@endpush