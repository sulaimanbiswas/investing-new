@extends('admin.layouts.app')

@section('title', 'Admin | Edit Gateway')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Gateway</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.gateways.update', $gateway) }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <!-- Basic Info -->
                            <div class="col-12 mb-4">
                                <div class="card border">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0 text-white">Basic Info</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Type</label>
                                                <select name="type" class="default-select form-control wide" required>
                                                    <option value="payment" @selected($gateway->type === 'payment')>Payment
                                                    </option>
                                                    <option value="withdrawal" @selected($gateway->type === 'withdrawal')>
                                                        Withdrawal</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Name</label>
                                                <input type="text" name="name" class="form-control"
                                                    value="{{ $gateway->name }}" required />
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Currency</label>
                                                <input type="text" name="currency" id="currency-input" class="form-control"
                                                    value="{{ $gateway->currency }}" required />
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Country</label>
                                                <input type="text" name="country" class="form-control"
                                                    value="{{ $gateway->country }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Range & Charge -->
                            <div class="col-12 mb-4">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div class="card border h-100">
                                            <div class="card-header bg-primary ">
                                                <h5 class="mb-0 text-white">Range</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">Minimum Limit</label>
                                                        <input type="number" name="min_limit"
                                                            class="form-control" value="{{ $gateway->min_limit }}" />
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">Maximum Limit</label>
                                                        <input type="number" name="max_limit"
                                                            class="form-control" value="{{ $gateway->max_limit }}" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="card border h-100">
                                            <div class="card-header bg-primary ">
                                                <h5 class="mb-0 text-white">Charge</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">Rate</label>
                                                        <div class="input-group mb-3 input-primary">
                                                            <span class="input-group-text">1
                                                                {{ strtoupper($gateway->currency) }}</span>
                                                            <input type="number" name="rate_usdt"
                                                                class="form-control" value="{{ $gateway->rate_usdt }}"
                                                                required />
                                                            <span class="input-group-text"
                                                                id="rate-suffix">{{ strtoupper($gateway->currency) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Charge Type</label>
                                                            <select name="charge_type" id="charge_type"
                                                                class="default-select form-control wide" required>
                                                                <option value="fixed"
                                                                    @selected($gateway->charge_type === 'fixed')>Fixed
                                                                </option>
                                                                <option value="percent"
                                                                    @selected($gateway->charge_type === 'percent')>Percent
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Charge Value</label>
                                                            <div class="input-group mb-3 input-primary">
                                                                <input type="number" name="charge_value"
                                                                    class="form-control" value="{{ $gateway->charge_value }}"
                                                                    required />
                                                                <span class="input-group-text" id="charge-value-suffix">{{ $gateway->charge_type === 'percent' ? '%' : strtoupper($gateway->currency) }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Deposit Instruction -->
                            <div class="col-12 mb-4">
                                <div class="card border">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0 text-white">Deposit Instruction</h5>
                                    </div>
                                    <div class="card-body">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" class="form-control"
                                            rows="6">{{ $gateway->description }}</textarea>
                                        <small class="text-muted">Supports rich text. Inline image upload is available on
                                            create.</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Deposit Details -->
                            <div class="col-12 mb-4">
                                <div class="card border">
                                    <div class="card-header bg-primary ">
                                        <h5 class="mb-0 text-white">Deposit Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Address</label>
                                                <input type="text" name="address" class="form-control"
                                                    value="{{ $gateway->address }}" />
                                                <div class="mt-4">
                                                    <div class="form-check custom-checkbox mb-3">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="requires_txn_id" value="1" id="requires_txn_id"
                                                            @checked($gateway->requires_txn_id)>
                                                        <label class="form-check-label" for="requires_txn_id">Require
                                                            Transaction ID</label>
                                                    </div>
                                                    <div class="form-check custom-checkbox mb-0">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="requires_screenshot" value="1" id="requires_screenshot"
                                                            @checked($gateway->requires_screenshot)>
                                                        <label class="form-check-label" for="requires_screenshot">Require
                                                            Screenshot</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">QR</label>
                                                <input type="text" name="qr_path" id="qr_path" class="form-control"
                                                    placeholder="e.g. /uploads/qrs/myqr.png"
                                                    value="{{ $gateway->qr_path }}" />
                                                <div id="qr-dropzone" class="dropzone mt-2 border rounded p-3">
                                                    <div class="dz-message">Drag & drop QR image here or click to upload
                                                    </div>
                                                </div>
                                                <small class="text-muted">Accepted: jpg, jpeg, png, webp. Max 5MB.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- User Data (editable) -->
                            <div class="col-12 mb-4">
                                <div class="card border">
                                    <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0 text-white">User Data</h5>
                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#editGenerateFormModal">+ Add New</button>
                                    </div>
                                    <div class="card-body">
                                        <div id="user-data-list" class="mb-3"></div>
                                        <div id="custom_fields_container">
@php
    $existingFields = $gateway->custom_fields;
    if (is_string($existingFields)) {
        $decoded = json_decode($existingFields, true);
        $existingFields = is_array($decoded) ? $decoded : [];
    }
@endphp
@if(is_array($existingFields))
    @foreach($existingFields as $f)
        <input type="hidden" name="custom_fields[]" value='@json($f)' />
    @endforeach
@endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-12 mb-3">
                                <div class="card border">
                                    <div class="card-header bg-primary ">
                                        <h5 class="mb-0 text-white">Status</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check custom-checkbox mb-0">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                                id="is_active" @checked($gateway->is_active)>
                                            <label class="form-check-label" for="is_active">Active</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="{{ route('admin.gateways.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@ckeditor/ckeditor5-build-classic@41.4.2/build/ckeditor.js"></script>
    <script>
        (function () {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
           
            // User Data Builder (edit/delete/add)
            const listEl = document.getElementById('user-data-list');
            const containerEl = document.getElementById('custom_fields_container');
            const renderItem = (item, idx) => {
                const div = document.createElement('div');
                div.className = 'p-3 mb-2 border rounded d-flex justify-content-between align-items-center';
                // Support legacy/alternate keys for label/type
                const label = item.label || item.name || item.form_label || item.title || 'No label';
                const type = item.type || item.field_type || item.form_type || item.input_type || 'field';
                const requiredTxt = item.required ? ' • required' : '';
                const optionsTxt = Array.isArray(item.options) && item.options.length ? ' • options: ' + item.options.join(', ') : '';
                const left = document.createElement('div');
                left.innerHTML = `<div class=\"fw-semibold\">${label}</div><div class=\"text-muted\">${type}${requiredTxt}${optionsTxt}</div>`;
                const actions = document.createElement('div');
                const editBtn = document.createElement('button');
                editBtn.type = 'button';
                editBtn.className = 'btn btn-warning btn-sm me-2';
                editBtn.innerHTML = '<i class="fa fa-pencil"></i>';
                editBtn.onclick = () => openModalForEdit(idx, item);
                const delBtn = document.createElement('button');
                delBtn.type = 'button';
                delBtn.className = 'btn btn-danger btn-sm';
                delBtn.innerHTML = '<i class="fa fa-times"></i>';
                delBtn.onclick = () => { const arr = readJson(); arr.splice(idx,1); writeJson(arr); };
                actions.appendChild(editBtn);
                actions.appendChild(delBtn);
                div.appendChild(left);
                div.appendChild(actions);
                return div;
            };
            const readJson = () => {
                const inputs = containerEl.querySelectorAll('input[name="custom_fields[]"]');
                const arr = [];
                inputs.forEach(i => {
                    let val = (i.value || '').trim();
                    if (!val) return;
                    // Try to parse; if result is a string, parse again (handles double-encoded values like "{\"type\":...}")
                    try {
                        let parsed = JSON.parse(val);
                        if (typeof parsed === 'string') {
                            try { parsed = JSON.parse(parsed); } catch(e2) {}
                        }
                        // Normalize keys and booleans
                        if (parsed && typeof parsed === 'object') {
                            if (typeof parsed.required !== 'boolean') {
                                parsed.required = parsed.required === true || parsed.required === 'true' || parsed.required === 1 || parsed.required === '1';
                            }
                            arr.push(parsed);
                        }
                    } catch(e) {}
                });
                return arr;
            };
            const writeJson = (arr) => {
                containerEl.innerHTML = '';
                if (arr && arr.length) {
                    arr.forEach(item => {
                        const inp = document.createElement('input');
                        inp.type = 'hidden';
                        inp.name = 'custom_fields[]';
                        inp.value = JSON.stringify(item);
                        containerEl.appendChild(inp);
                    });
                } else {
                    const inp = document.createElement('input');
                    inp.type = 'hidden';
                    inp.name = 'custom_fields[]';
                    inp.value = '';
                    containerEl.appendChild(inp);
                }
                if (!arr || arr.length === 0) { listEl.innerHTML = '<div class="text-muted">No data</div>'; return; }
                listEl.innerHTML = '';
                arr.forEach((item, idx) => listEl.appendChild(renderItem(item, idx)));
            };
            writeJson(readJson());

            // Live update rate currency from Currency input
            const currencyInput = document.getElementById('currency-input');
            const rateSuffixEl = document.getElementById('rate-suffix');
            const chargeTypeSelect = document.getElementById('charge_type');
            const chargeValueSuffixEl = document.getElementById('charge-value-suffix');
            if (currencyInput && rateSuffixEl) {
                const updateCurrency = () => {
                    const val = (currencyInput.value || '').trim();
                    const show = val ? val.toUpperCase() : rateSuffixEl.textContent || 'USDT';
                    // update both prefix and suffix texts
                    const prefixEl = rateSuffixEl.parentElement?.querySelector('.input-group-text:first-child');
                    if (prefixEl) prefixEl.textContent = `1 ${show}`;
                    rateSuffixEl.textContent = show;
                    // Update charge value suffix only if not percent
                    if (chargeTypeSelect && chargeValueSuffixEl && chargeTypeSelect.value !== 'percent') {
                        chargeValueSuffixEl.textContent = show;
                    }
                };
                currencyInput.addEventListener('input', updateCurrency);
                updateCurrency();
            }

            // Update Charge Value suffix based on type
            const updateChargeValueSuffix = () => {
                if (!chargeValueSuffixEl) return;
                if (chargeTypeSelect && chargeTypeSelect.value === 'percent') {
                    chargeValueSuffixEl.textContent = '%';
                } else {
                    const cur = (currencyInput?.value || (rateSuffixEl?.textContent || 'USDT')).trim().toUpperCase() || 'USDT';
                    chargeValueSuffixEl.textContent = cur;
                }
            };
            if (chargeTypeSelect) {
                chargeTypeSelect.addEventListener('change', updateChargeValueSuffix);
                updateChargeValueSuffix();
            }

            // CKEditor for description
            if (document.querySelector('textarea[name="description"]')) {
                ClassicEditor
                    .create(document.querySelector('textarea[name="description"]'), {
                        simpleUpload: {
                            uploadUrl: '{{ url('/admin/uploads/ckeditor') }}',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            }
                        }
                    })
                    .then(editor => console.info('[Gateways Edit] CKEditor ready'))
                    .catch(error => console.error('[Gateways Edit] CKEditor init failed', error));
            }

            // Dropzone for QR upload
            if (document.getElementById('qr-dropzone')) {
                const ensureDropzone = () => new Promise((resolve) => {
                    if (window.Dropzone) return resolve();
                    const css = document.createElement('link');
                    css.rel = 'stylesheet';
                    css.href = 'https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.css';
                    document.head.appendChild(css);
                    const script = document.createElement('script');
                    script.src = 'https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.js';
                    script.onload = () => resolve();
                    document.body.appendChild(script);
                });

                ensureDropzone().then(() => {
                    Dropzone.autoDiscover = false;
                    const dz = new Dropzone('#qr-dropzone', {
                        url: '{{ url('/admin/uploads/qr') }}',
                        method: 'post',
                        headers: { 'X-CSRF-TOKEN': token },
                        maxFiles: 1,
                        maxFilesize: 5,
                        acceptedFiles: 'image/jpeg,image/png,image/webp,image/jpg',
                        addRemoveLinks: true,
                        dictDefaultMessage: 'Drag & drop QR image here or click to upload',
                    });

                    // Preload existing QR image if present
                    const existingPathInput = document.getElementById('qr_path');
                    const existingPath = (existingPathInput?.value || '').trim();
                    if (existingPath) {
                        const existingUrl = existingPath.startsWith('http') ? existingPath : '{{ asset('') }}' + existingPath.replace(/^\//, '');
                        const mockFile = { name: existingPath.split('/').pop(), size: 0, accepted: true };
                        dz.emit('addedfile', mockFile);
                        dz.emit('thumbnail', mockFile, existingUrl);
                        dz.emit('success', mockFile, { path: existingPath, url: existingUrl });
                        dz.emit('complete', mockFile);
                        mockFile.previewElement.classList.add('dz-success', 'dz-complete');
                        dz.files.push(mockFile);
                    }

                    dz.on('success', function (file, response) {
                        if (response && response.path) {
                            document.getElementById('qr_path').value = response.path;
                        } else if (response && response.url) {
                            document.getElementById('qr_path').value = response.url;
                        }
                    });

                    dz.on('error', function (file, errorMessage) {
                        console.error('[Gateways Edit] QR upload failed', errorMessage);
                    });

                    dz.on('removedfile', function () {
                        const path = document.getElementById('qr_path').value;
                        if (!path) return;
                        fetch('{{ url('/admin/uploads/qr/delete') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ path })
                        }).then(r => r.json()).then(res => {
                            document.getElementById('qr_path').value = '';
                            console.info('[Gateways Edit] QR deleted:', res.deleted === true);
                        }).catch(err => console.error('[Gateways Edit] QR delete failed', err));
                    });
                });
            }
            // Modal logic for add/edit user data
            const modalHtml = `
<div class=\"modal fade\" id=\"editGenerateFormModal\" tabindex=\"-1\" aria-hidden=\"true\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n      <div class=\"modal-header\">\n        <h5 class=\"modal-title\">User Field</h5>\n        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>\n      </div>\n      <div class=\"modal-body\">\n        <div class=\"mb-3\">\n          <label class=\"form-label\">Type <span class=\"text-danger\">*</span></label>\n          <select id=\"ud-type\" class=\"default-select form-control wide\">\n            <option value=\"\" selected>Select One</option>\n            <option value=\"text\">Text</option>\n            <option value=\"textarea\">Textarea</option>\n            <option value=\"select\">Select</option>\n            <option value=\"checkbox\">Checkbox</option>\n            <option value=\"radio\">Radio</option>\n            <option value=\"file\">File</option>\n          </select>\n        </div>\n        <div class=\"mb-3\">\n          <label class=\"form-label\">Required <span class=\"text-danger\">*</span></label>\n          <select id=\"ud-required\" class=\"default-select form-control wide\">\n            <option value=\"\" selected>Select One</option>\n            <option value=\"true\">Yes</option>\n            <option value=\"false\">No</option>\n          </select>\n        </div>\n        <div class=\"mb-3\">\n          <label class=\"form-label\">Label <span class=\"text-danger\">*</span></label>\n          <input type=\"text\" id=\"ud-label\" class=\"form-control\" placeholder=\"Label\" />\n        </div>\n        <div class=\"mb-3\" id=\"ud-options-wrap\" style=\"display:none;\">\n          <label class=\"form-label\">Options (comma separated)</label>\n          <input type=\"text\" id=\"ud-options\" class=\"form-control\" placeholder=\"Option1, Option2\" />\n        </div>\n        <div class=\"alert alert-danger d-none\" id=\"ud-error\"></div>\n      </div>\n      <div class=\"modal-footer\">\n        <button type=\"button\" class=\"btn btn-danger light\" data-bs-dismiss=\"modal\">Close</button>\n        <button type=\"button\" class=\"btn btn-primary\" id=\"ud-save-btn\">Save</button>\n      </div>\n    </div>\n  </div>\n</div>`;
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const typeEl = document.getElementById('ud-type');
            const reqEl = document.getElementById('ud-required');
            const labelEl = document.getElementById('ud-label');
            const optsWrap = document.getElementById('ud-options-wrap');
            const optsEl = document.getElementById('ud-options');
            const errEl = document.getElementById('ud-error');
            const saveBtn = document.getElementById('ud-save-btn');
            typeEl.addEventListener('change', () => {
                if (['select','checkbox','radio'].includes(typeEl.value)) { optsWrap.style.display=''; } else { optsWrap.style.display='none'; optsEl.value=''; }
            });
            const openModalForEdit = (idx, item) => {
                saveBtn.dataset.editIndex = idx;
                const typeVal = item.type || item.field_type || item.form_type || item.input_type || '';
                typeEl.value = typeVal;
                reqEl.value = item.required ? 'true' : 'false';
                labelEl.value = item.label || item.name || item.form_label || item.title || '';
                if (['select','checkbox','radio'].includes(typeVal)) {
                    optsWrap.style.display='';
                    optsEl.value = (item.options||[]).join(', ');
                } else { optsWrap.style.display='none'; optsEl.value=''; }
                bootstrap.Modal.getOrCreateInstance(document.getElementById('editGenerateFormModal')).show();
            };
            saveBtn.addEventListener('click', () => {
                errEl.classList.add('d-none'); errEl.textContent='';
                const type = typeEl.value; const required = reqEl.value; const label = labelEl.value.trim();
                if (!type || !required || !label) { errEl.textContent='Please fill all required fields'; errEl.classList.remove('d-none'); return; }
                const item = { type, label, required: required==='true' };
                if (['select','checkbox','radio'].includes(type)) { const opts = (optsEl.value||'').split(',').map(s=>s.trim()).filter(Boolean); item.options = opts; }
                const arr = readJson();
                const editIndex = saveBtn.dataset.editIndex;
                if (editIndex !== undefined && editIndex !== '') { arr[parseInt(editIndex,10)] = item; saveBtn.dataset.editIndex=''; } else { arr.push(item); }
                writeJson(arr);
                bootstrap.Modal.getOrCreateInstance(document.getElementById('editGenerateFormModal')).hide();
                typeEl.value=''; reqEl.value=''; labelEl.value=''; optsEl.value=''; optsWrap.style.display='none';
            });
        })();
    </script>
@endpush