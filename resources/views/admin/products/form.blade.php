@csrf
<div class="row">
    <div class="col-md-5 mb-3">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
    </div>
    <div class="col-md-7 mb-3">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Price <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="price" class="form-control"
                    value="{{ old('price', $product->price ?? '') }}" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                <input type="number" name="quantity" class="form-control"
                    value="{{ old('quantity', $product->quantity ?? '100000000') }}" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Platform <span class="text-danger">*</span></label>
                <select name="platform_id" class="default-select form-control wide" required>
                    <option value="" class="d-none">Select Platform</option>
                    @foreach($platforms as $platform)
                        <option value="{{ $platform->id }}" {{ (string) old('platform_id', $product->platform_id ?? '') === (string) $platform->id ? 'selected' : '' }}>
                            {{ $platform->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-12 mb-3">
        <label class="form-label">Image</label>
        <input type="text" name="image" id="product_image" class="form-control mb-2"
            value="{{ old('image', $product->image ?? '') }}" placeholder="/uploads/products/product.png">
        <div id="product-image-dropzone" class="dropzone border rounded p-3">
            <div class="dz-message text-center">
                <i class="fas fa-cloud-upload-alt text-primary" style="font-size: 48px; margin-bottom: 10px;"></i>
                <p class="mb-0">Drag & drop image here or click to upload</p>
            </div>
        </div>

    </div>

</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" id="product_description" class="form-control"
        rows="4">{{ old('description', $product->description ?? '') }}</textarea>
</div>
@if(isset($product))
    <div class=" mb-3">
        <label class="form-label">Status</label>
        <select name="is_active" class="default-select form-control wide" required>
            <option value="1" {{ old('is_active', $product->is_active) ? 'selected' : '' }}>Active</option>
            <option value="0" {{ !old('is_active', $product->is_active) ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
@endif