@csrf
<div class="row">
    <div class="{{ isset($rule) ? "col-md-4" : "col-md-6" }} mb-3">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $rule->name ?? '') }}" required>
    </div>
    <div class="{{ isset($rule) ? "col-md-4" : "col-md-6" }} mb-3">
        <label class="form-label">Sort By <span class="text-danger">*</span></label>
        <input type="number" name="sort_by" class="form-control" value="{{ old('sort_by', $rule->sort_by ?? '') }}"
            required>
    </div>
    @if(isset($rule))
        <div class="{{ isset($rule) ? "col-md-4" : "col-md-6" }} mb-3">
            <label class="form-label">Status</label>
            <select name="is_active" class="default-select form-control wide" required>
                <option value="1" {{ (string) old('is_active', $rule->is_active ?? '') === '1' ? 'selected' : '' }}>Active
                </option>
                <option value="0" {{ (string) old('is_active', $rule->is_active ?? '') === '0' ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>
        </div>
    @endif
    <div class="col-md-12 mb-3">
        <label class="form-label">Image</label>
        <input type="text" name="image" id="rule_image" class="form-control mb-2"
            value="{{ old('image', $rule->image ?? '') }}" placeholder="/uploads/platform-rules/example.png">
        <div id="rule-image-dropzone" class="dropzone border rounded p-3">
            <div class="dz-message text-center">
                <i class="fas fa-cloud-upload-alt text-primary" style="font-size: 48px; margin-bottom: 10px;"></i>
                <p class="mb-0">Drag & drop image here or click to upload</p>
            </div>
        </div>
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" id="rule_description" class="form-control"
        rows="4">{{ old('description', $rule->description ?? '') }}</textarea>
</div>