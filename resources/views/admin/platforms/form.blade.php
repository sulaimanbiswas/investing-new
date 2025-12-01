@csrf
<div class="row">
    <div class="col-md-3 mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $platform->name ?? '') }}" required>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Commission (%)</label>
        <input type="number" step="0.01" name="commission" class="form-control"
            value="{{ old('commission', $platform->commission ?? 0) }}" required>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Start Price</label>
        <input type="number" step="0.01" name="start_price" class="form-control"
            value="{{ old('start_price', $platform->start_price ?? 0) }}" required>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">End Price</label>
        <input type="number" step="0.01" name="end_price" class="form-control"
            value="{{ old('end_price', $platform->end_price ?? 0) }}" required>
    </div>
    <div class="col-md-12 mb-3">
        <label class="form-label">Image (path or URL)</label>
        <input type="text" name="image" id="platform_image" class="form-control"
            value="{{ old('image', $platform->image ?? '') }}" placeholder="e.g. /uploads/platforms/myimage.png">
        <div id="platform-image-dropzone" class="dropzone mt-2 border rounded p-3">
            <div class="dz-message text-center">
                <i class="fas fa-cloud-upload-alt text-primary" style="font-size: 48px; margin-bottom: 10px;"></i>
                <p class="mb-0">Drag & drop image here or click to upload</p>
            </div>
        </div>
        <small class="text-muted">Accepted: jpg, jpeg, png, webp. Max 5MB. Uses same uploader as gateway QR.</small>
    </div>
</div>