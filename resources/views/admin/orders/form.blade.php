@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Order Set <span class="text-danger">*</span></label>
        <select name="order_set_id" id="order_set_id" class="form-control" required>
            <option value="">Select Order Set</option>
            @foreach($orderSets as $orderSet)
                <option value="{{ $orderSet->id }}" {{ (string) old('order_set_id', $order->order_set_id ?? '') === (string) $orderSet->id ? 'selected' : '' }}>
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
            <option value="single" {{ old('type', $order->type ?? '') === 'single' ? 'selected' : '' }}>Single</option>
            <option value="combo" {{ old('type', $order->type ?? '') === 'combo' ? 'selected' : '' }}>Combo</option>
        </select>
        @error('type')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Profit % <span class="text-danger">*</span></label>
        <input type="number" name="profit_percentage" id="profit_percentage" class="form-control"
            value="{{ old('profit_percentage', $order->profit_percentage ?? '') }}" min="0" max="100" step="0.01"
            required>
        @error('profit_percentage')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-3">
        <label class="form-label">Platform <span class="text-danger">*</span></label>
        <select name="platform_id" id="platform_id" class="form-control" required>
            <option value="">Select Platform</option>
            @foreach($platforms as $platform)
                <option value="{{ $platform->id }}" {{ (string) old('platform_id', $order->platform_id ?? '') === (string) $platform->id ? 'selected' : '' }}>
                    {{ $platform->name }}
                </option>
            @endforeach
        </select>
        @error('platform_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>

@if(isset($order))
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="is_active" class="form-control">
            <option value="1" {{ old('is_active', $order->is_active) ? 'selected' : '' }}>Active</option>
            <option value="0" {{ !old('is_active', $order->is_active) ? 'selected' : '' }}>Inactive</option>
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
        @if(isset($order) && $order->orderProducts->count() > 0)
            @foreach($order->orderProducts as $index => $item)
                <div class="product-row card mb-2 p-3" data-index="{{ $index }}">
                    <div class="row align-items-end">
                        <div class="col-md-5">
                            <label class="form-label">Product</label>
                            <select name="products[{{ $index }}][product_id]" class="form-control product-select" required>
                                <option value="">Select Product</option>
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
                                value="{{ $item->price }}" min="0" step="0.01" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm remove-product w-100">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="product-row card mb-2 p-3" data-index="0">
                <div class="row align-items-end">
                    <div class="col-md-5">
                        <label class="form-label">Product</label>
                        <select name="products[0][product_id]" class="form-control product-select" required>
                            <option value="">Select Product</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="products[0][quantity]" class="form-control quantity-input" value="1"
                            min="1" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Price</label>
                        <input type="number" name="products[0][price]" class="form-control price-input" value="0" min="0"
                            step="0.01" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-product w-100">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="card bg-light p-3 mb-3">
    <h5 class="mb-3">Order Summary</h5>
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
    <script>
        let productIndex = {{ isset($order) && $order->orderProducts->count() > 0 ? $order->orderProducts->count() : 1 }};
        let availableProducts = [];
        const editMode = {{ isset($order) ? 'true' : 'false' }};
        @php
            $existingProductsData = isset($order) ? $order->orderProducts->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'price' => $item->price,
                    'quantity' => $item->quantity
                ];
            })->toArray() : [];
        @endphp
        const existingProducts = @json($existingProductsData);

        // Fetch products when platform changes
        document.getElementById('platform_id').addEventListener('change', function () {
            const platformId = this.value;
            if (!platformId) {
                availableProducts = [];
                updateAllProductSelects();
                return;
            }

            fetch(`{{ route('admin.orders.products') }}?platform_id=${platformId}`)
                .then(response => response.json())
                .then(data => {
                    availableProducts = data;
                    updateAllProductSelects();
                });
        });

        function updateAllProductSelects() {
            document.querySelectorAll('.product-select').forEach((select, index) => {
                const currentValue = select.value;
                select.innerHTML = '<option value="">Select Product</option>';

                availableProducts.forEach(product => {
                    const option = document.createElement('option');
                    option.value = product.id;
                    option.textContent = product.name;
                    option.dataset.price = product.price;

                    if (editMode && existingProducts[index] && existingProducts[index].product_id == product.id) {
                        option.selected = true;
                    } else if (currentValue == product.id) {
                        option.selected = true;
                    }

                    select.appendChild(option);
                });
            });
        }

        // Add product row
        document.getElementById('addProductRow').addEventListener('click', function () {
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

            // Populate the new select with products
            const newSelect = newRow.querySelector('.product-select');
            availableProducts.forEach(product => {
                const option = document.createElement('option');
                option.value = product.id;
                option.textContent = product.name;
                option.dataset.price = product.price;
                newSelect.appendChild(option);
            });

            attachRowEvents(newRow);
        });

        // Remove product row
        document.addEventListener('click', function (e) {
            if (e.target.closest('.remove-product')) {
                const row = e.target.closest('.product-row');
                if (document.querySelectorAll('.product-row').length > 1) {
                    row.remove();
                    calculateTotals();
                } else {
                    alert('At least one product is required');
                }
            }
        });

        // Auto-fill price when product is selected
        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('product-select')) {
                const selectedOption = e.target.options[e.target.selectedIndex];
                const price = selectedOption.dataset.price || 0;
                const row = e.target.closest('.product-row');
                const priceInput = row.querySelector('.price-input');
                priceInput.value = price;
                calculateTotals();
            }
        });

        // Calculate totals on quantity/price change
        document.addEventListener('input', function (e) {
            if (e.target.classList.contains('quantity-input') ||
                e.target.classList.contains('price-input') ||
                e.target.id === 'profit_percentage') {
                calculateTotals();
            }
        });

        function attachRowEvents(row) {
            const select = row.querySelector('.product-select');
            const quantityInput = row.querySelector('.quantity-input');
            const priceInput = row.querySelector('.price-input');

            select.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const price = selectedOption.dataset.price || 0;
                priceInput.value = price;
                calculateTotals();
            });

            quantityInput.addEventListener('input', calculateTotals);
            priceInput.addEventListener('input', calculateTotals);
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

            document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
            document.getElementById('profitAmount').textContent = '$' + profitAmount.toFixed(2);
            document.getElementById('totalWithProfit').textContent = '$' + totalWithProfit.toFixed(2);
            document.getElementById('expectedIncome').textContent = '$' + totalWithProfit.toFixed(2);
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function () {
            // Attach events to existing rows
            document.querySelectorAll('.product-row').forEach(attachRowEvents);

            // Load products if platform is already selected (edit mode)
            const platformId = document.getElementById('platform_id').value;
            if (platformId) {
                fetch(`{{ route('admin.orders.products') }}?platform_id=${platformId}`)
                    .then(response => response.json())
                    .then(data => {
                        availableProducts = data;
                        updateAllProductSelects();
                        calculateTotals();
                    });
            }

            calculateTotals();
        });
    </script>
@endpush