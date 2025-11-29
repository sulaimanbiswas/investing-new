@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $rule->name ?? '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Sort By <span class="text-danger">*</span></label>
        <input type="number" name="sort_by" class="form-control" value="{{ old('sort_by', $rule->sort_by ?? '') }}"
            required>
    </div>
    <div class="col-md-12 mb-3">
        <label class="form-label">Image</label>
        <input type="text" name="image" id="rule_image" class="form-control mb-2"
            value="{{ old('image', $rule->image ?? '') }}" placeholder="/uploads/platform-rules/example.png">
        <div id="rule-image-dropzone" class="dropzone border rounded p-3"></div>
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" id="rule_description" class="form-control"
        rows="4">{{ old('description', $rule->description ?? '') }}</textarea>
</div>
@if(isset($rule))
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="is_active" class="form-select">
            <option value="1" {{ old('is_active', $rule->is_active) ? 'selected' : '' }}>Active</option>
            <option value="0" {{ !old('is_active', $rule->is_active) ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
@endif