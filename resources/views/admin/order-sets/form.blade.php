@csrf
<div class="row">
    <div class="{{ isset($orderSet) ? "col-md-4" : "col-md-6" }} mb-3">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $orderSet->name ?? '') }}" required>
    </div>
    <div class="{{ isset($orderSet) ? "col-md-4" : "col-md-6" }} mb-3">
        <label class="form-label">Platform <span class="text-danger">*</span></label>
        <select name="platform_id" class="default-select form-control wide" required>
            <option value="" class="d-none">Select Platform</option>
            @foreach($platforms as $platform)
                <option value="{{ $platform->id }}" {{ (string) old('platform_id', $orderSet->platform_id ?? '') === (string) $platform->id ? 'selected' : '' }}>
                    {{ $platform->name }}
                </option>
            @endforeach
        </select>
    </div>
    @if(isset($orderSet))
        <div class="{{ isset($orderSet) ? "col-md-4" : "col-md-6" }} mb-3">
            <label class="form-label">Status</label>
            <select name="is_active" class="default-select form-control wide" required>
                <option value="1" {{ (string) old('is_active', $orderSet->is_active ?? '') === '1' ? 'selected' : '' }}>Active
                </option>
                <option value="0" {{ (string) old('is_active', $orderSet->is_active ?? '') === '0' ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>
        </div>
    @endif
</div>