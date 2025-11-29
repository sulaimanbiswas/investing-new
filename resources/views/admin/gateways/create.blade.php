@extends('admin.layouts.app')

@section('title', 'Admin | Create Gateway')

@section('content')
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/admin/gateways">Gateways</a>
            </li>
            <li class="breadcrumb-item active">
                <a href="javascript:void(0)">Create Gateways</a>
            </li>
        </ol>
    </div>
    @if(session('status'))
        <div class="alert alert-success solid alert-dismissible fade show">
            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"
                stroke-linecap="round" stroke-linejoin="round" class="me-2">
                <polyline points="9 11 12 14 22 4"></polyline>
                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
            </svg>
            <strong>Success!</strong> {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger solid alert-dismissible fade show">
            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"
                stroke-linecap="round" stroke-linejoin="round" class="me-2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            <strong>Validation Failed</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger solid alert-dismissible fade show">
            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"
                stroke-linecap="round" stroke-linejoin="round" class="me-2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create Gateway</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.gateways.store') }}">
                        @csrf
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
                                                <select name="type" id="gateway-type"
                                                    class="default-select form-control wide" required>
                                                    <option value="payment">Payment</option>
                                                    <option value="withdrawal">Withdrawal</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Name</label>
                                                <input type="text" name="name" class="form-control" required />
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Currency</label>
                                                <input type="text" name="currency" id="currency-input" class="form-control"
                                                    placeholder="USDT, BDT, USD" required />
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Country</label>
                                                <input type="text" name="country" class="form-control"
                                                    placeholder="Optional" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Range & Charge (both types share) -->
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
                                                        <div class="input-group mb-3 input-primary">
                                                            <input type="number" step="0.01" name="min_limit"
                                                                class="form-control" required>
                                                            <span class="input-group-text">USDT</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">Maximum Limit</label>
                                                        <div class="input-group mb-3 input-primary">
                                                            <input type="number" step="0.01" name="max_limit"
                                                                class="form-control" required>
                                                            <span class="input-group-text">USDT</span>
                                                        </div>
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
                                                            <span class="input-group-text">1 USDT</span>
                                                            <input type="number" step="0.01" name="rate_usdt"
                                                                class="form-control" required>
                                                            <span class="input-group-text" id="rate-suffix">USDT</span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Charge Type</label>
                                                            <select name="charge_type" id="charge_type"
                                                                class="default-select form-control wide" required>
                                                                <option value="fixed">Fixed</option>
                                                                <option value="percent">Percent</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3 ">
                                                            <label class="form-label">Charge Value</label>
                                                            <div class="input-group  input-primary">
                                                                <input type="number" step="0.01" name="charge_value"
                                                                    class="form-control" required>
                                                                <span class="input-group-text"
                                                                    id="charge-value-suffix">USDT</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Deposit Instruction (payment only) -->
                            <div class="col-12 mb-4 type-payment">
                                <div class="card border">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0 text-white">Deposit Instruction</h5>
                                    </div>
                                    <div class="card-body">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" id="gateway-description" class="form-control"
                                            rows="6"></textarea>
                                        <small class="text-muted">Supports rich text and inline image upload.</small>
                                    </div>
                                </div>
                            </div>
                            <!-- Deposit Address & QR (payment only) -->
                            <div class="col-12 mb-4 type-payment">
                                <div class="card border">
                                    <div class="card-header bg-primary ">
                                        <h5 class="mb-0 text-white">Deposit Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Address</label>
                                                <input type="text" name="address" class="form-control" />
                                                <div class="mt-4">
                                                    <div class="form-check custom-checkbox mb-3">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="requires_txn_id" value="1" id="requires_txn_id">
                                                        <label class="form-check-label" for="requires_txn_id">Require
                                                            Transaction ID</label>
                                                    </div>
                                                    <div class="form-check custom-checkbox mb-0">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="requires_screenshot" value="1" id="requires_screenshot">
                                                        <label class="form-check-label" for="requires_screenshot">Require
                                                            Screenshot</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">QR</label>
                                                <input type="text" name="qr_path" id="qr_path" class="form-control"
                                                    placeholder="e.g. /uploads/qrs/myqr.png" />
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

                            <!-- User Data (withdrawal only) -->
                            <div class="col-12 mb-4 type-withdrawal">
                                <div class="card border">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white">User Data</h5>
                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                            data-bs-target="#generateFormModal">+ Add New</button>
                                    </div>
                                    <div class="card-body">
                                        <div id="user-data-list" class="mb-3"></div>
                                        <div id="custom_fields_container"></div>
                                        {{-- <label class="form-label">Custom Fields (JSON)</label>
                                        <textarea name="custom_fields" id="custom_fields" class="form-control" rows="4"
                                            placeholder='[{"type":"text","label":"Account ID","required":true}]'></textarea>
                                        <small class="text-muted">Supports types: text, textarea, select, checkbox, radio,
                                            file</small> --}}
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="is_active" value="1" />
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
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

            // Toggle fields by gateway type (payment vs withdrawal)
            const typeSelect = document.getElementById('gateway-type');
            const paymentBlocks = document.querySelectorAll('.type-payment');
            const withdrawalBlocks = document.querySelectorAll('.type-withdrawal');
            const applyTypeVisibility = () => {
                const type = typeSelect ? typeSelect.value : 'payment';
                paymentBlocks.forEach(el => el.style.display = (type === 'payment') ? '' : 'none');
                withdrawalBlocks.forEach(el => el.style.display = (type === 'withdrawal') ? '' : 'none');
            };
            if (typeSelect) {
                typeSelect.addEventListener('change', applyTypeVisibility);
                applyTypeVisibility();
            }

            // Live update rate currency from Currency input
            const currencyInput = document.getElementById('currency-input');
            const rateSuffixEl = document.getElementById('rate-suffix');
            const chargeTypeSelect = document.getElementById('charge_type');
            const chargeValueSuffixEl = document.getElementById('charge-value-suffix');
            if (currencyInput && rateSuffixEl) {
                const updateCurrency = () => {
                    const val = (currencyInput.value || '').trim();
                    const show = val ? val.toUpperCase() : 'USDT';
                    rateSuffixEl.textContent = show;
                    // Only update charge value suffix if NOT percent
                    if (chargeTypeSelect && chargeValueSuffixEl && chargeTypeSelect.value !== 'percent') {
                        chargeValueSuffixEl.textContent = show;
                    }
                };
                currencyInput.addEventListener('input', updateCurrency);
                updateCurrency();
            }
            const updateChargeValueSuffix = () => {
                if (!chargeValueSuffixEl) return;
                if (chargeTypeSelect && chargeTypeSelect.value === 'percent') {
                    chargeValueSuffixEl.textContent = '%';
                } else {
                    const cur = (currencyInput?.value || 'USDT').trim().toUpperCase() || 'USDT';
                    chargeValueSuffixEl.textContent = cur;
                }
            };
            if (chargeTypeSelect) {
                chargeTypeSelect.addEventListener('change', updateChargeValueSuffix);
                updateChargeValueSuffix();
            }
            if (!document.getElementById('gateway-description')) return;
            ClassicEditor
                .create(document.getElementById('gateway-description'), {
                    simpleUpload: {
                        // Use explicit URL to avoid breaking when route name is missing
                        uploadUrl: '{{ url('/admin/uploads/ckeditor') }}',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        }
                    }
                })
                .then(editor => {
                    window.gatewayDescriptionEditor = editor;
                    console.info('[Gateways] CKEditor ready');
                })
                .catch(error => {
                    console.error('[Gateways] CKEditor init failed', error);
                });
            // Init Dropzone for QR upload
            if (document.getElementById('qr-dropzone')) {
                // Load Dropzone dynamically if not present
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
                        url: '{{ url('/admin/uploads/qrs') }}',
                        method: 'post',
                        headers: { 'X-CSRF-TOKEN': token },
                        maxFiles: 1,
                        maxFilesize: 5, // MB
                        acceptedFiles: 'image/jpeg,image/png,image/webp,image/jpg',
                        addRemoveLinks: true,
                        dictDefaultMessage: 'Drag & drop QR image here or click to upload',
                    });

                    dz.on('success', function (file, response) {
                        if (response && response.path) {
                            document.getElementById('qr_path').value = response.path;
                        } else if (response && response.url) {
                            document.getElementById('qr_path').value = response.url;
                        }
                    });

                    dz.on('error', function (file, errorMessage) {
                        console.error('[Gateways] QR upload failed', errorMessage);
                    });

                    dz.on('removedfile', function (file) {
                        const path = document.getElementById('qr_path').value;
                        if (!path) return;
                        fetch('{{ url('/admin/uploads/qrs/delete') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ path })
                        }).then(r => r.json()).then(res => {
                            document.getElementById('qr_path').value = '';
                            console.info('[Gateways] QR deleted:', res.deleted === true);
                        }).catch(err => console.error('[Gateways] QR delete failed', err));
                    });
                });
            }
        })();
    </script>
@endpush
@push('scripts')
    <!-- Generate Form Modal -->
    <div class="modal fade" id="generateFormModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Generate Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Form Type <span class="text-danger">*</span></label>
                        <select id="gf-type" class="default-select form-control wide">
                            <option value="" selected>Select One</option>
                            <option value="text">Text</option>
                            <option value="textarea">Textarea</option>
                            <option value="select">Select</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="radio">Radio</option>
                            <option value="file">File</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Is Required <span class="text-danger">*</span></label>
                        <select id="gf-required" class="default-select form-control wide">
                            <option value="" selected>Select One</option>
                            <option value="true">Yes</option>
                            <option value="false">No</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Form Label <span class="text-danger">*</span></label>
                        <input type="text" id="gf-label" class="form-control" placeholder="Label" />
                    </div>
                    <div class="mb-3" id="gf-options-wrap" style="display:none;">
                        <label class="form-label">Options (comma separated)</label>
                        <input type="text" id="gf-options" class="form-control" placeholder="Option1, Option2" />
                    </div>
                    <div class="alert alert-danger d-none" id="gf-error"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="gf-add-btn">Add</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const listEl = document.getElementById('user-data-list');
            const containerEl = document.getElementById('custom_fields_container');
            const typeEl = document.getElementById('gf-type');
            const reqEl = document.getElementById('gf-required');
            const labelEl = document.getElementById('gf-label');
            const optsWrap = document.getElementById('gf-options-wrap');
            const optsEl = document.getElementById('gf-options');
            const errEl = document.getElementById('gf-error');

            const renderItem = (item, idx) => {
                const div = document.createElement('div');
                div.className = 'p-3 mb-2 border rounded d-flex justify-content-between align-items-center';
                const left = document.createElement('div');
                left.innerHTML = `<div class=\"fw-semibold\">${item.label}</div><div class=\"text-muted\">${item.type}${item.required ? ' • required' : ''}${item.options ? ' • options: ' + item.options.join(', ') : ''}</div>`;
                const actions = document.createElement('div');
                const editBtn = document.createElement('button');
                editBtn.type = 'button';
                editBtn.className = 'btn btn-warning me-2';
                editBtn.innerHTML = '<i class="fa fa-pencil"></i>';
                editBtn.onclick = () => {
                    typeEl.value = item.type;
                    reqEl.value = item.required ? 'true' : 'false';
                    labelEl.value = item.label;
                    if (['select', 'checkbox', 'radio'].includes(item.type)) {
                        optsWrap.style.display = '';
                        optsEl.value = (item.options || []).join(', ');
                    } else {
                        optsWrap.style.display = 'none';
                        optsEl.value = '';
                    }
                    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('generateFormModal'));
                    modal.show();
                    // on add, replace this index
                    document.getElementById('gf-add-btn').dataset.editIndex = idx;
                };
                const delBtn = document.createElement('button');
                delBtn.type = 'button';
                delBtn.className = 'btn btn-danger';
                delBtn.innerHTML = '<i class="fa fa-times"></i>';
                delBtn.onclick = () => {
                    const arr = readJson();
                    arr.splice(idx, 1);
                    writeJson(arr);
                };
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
                    if (i.value) {
                        try { arr.push(JSON.parse(i.value)); } catch (e) { }
                    }
                });
                return arr;
            };
            const writeJson = (arr) => {
                // rebuild hidden inputs as array items
                containerEl.innerHTML = '';
                if (arr && arr.length > 0) {
                    arr.forEach(item => {
                        const inp = document.createElement('input');
                        inp.type = 'hidden';
                        inp.name = 'custom_fields[]';
                        inp.value = JSON.stringify(item);
                        containerEl.appendChild(inp);
                    });
                } else {
                    // ensure it still submits as an array to satisfy validation rule
                    const inp = document.createElement('input');
                    inp.type = 'hidden';
                    inp.name = 'custom_fields[]';
                    inp.value = '';
                    containerEl.appendChild(inp);
                }
                // re-render list preview
                if (!arr || arr.length === 0) {
                    listEl.innerHTML = '<div class="text-muted">No data</div>';
                    return;
                }
                listEl.innerHTML = '';
                arr.forEach((item, idx) => listEl.appendChild(renderItem(item, idx)));
            };

            // initial render from any existing value
            writeJson(readJson());

            typeEl.addEventListener('change', () => {
                if (['select', 'checkbox', 'radio'].includes(typeEl.value)) {
                    optsWrap.style.display = '';
                } else {
                    optsWrap.style.display = 'none';
                }
            });

            document.getElementById('gf-add-btn').addEventListener('click', () => {
                errEl.classList.add('d-none');
                errEl.textContent = '';
                const type = typeEl.value;
                const required = reqEl.value;
                const label = labelEl.value.trim();
                if (!type || !required || !label) {
                    errEl.textContent = 'Please fill all required fields';
                    errEl.classList.remove('d-none');
                    return;
                }
                const item = { type, label, required: required === 'true' };
                if (['select', 'checkbox', 'radio'].includes(type)) {
                    const opts = (optsEl.value || '').split(',').map(s => s.trim()).filter(Boolean);
                    item.options = opts;
                }
                const arr = readJson();
                const editIndex = document.getElementById('gf-add-btn').dataset.editIndex;
                if (editIndex !== undefined && editIndex !== '') {
                    arr[parseInt(editIndex, 10)] = item;
                    document.getElementById('gf-add-btn').dataset.editIndex = '';
                } else {
                    arr.push(item);
                }
                writeJson(arr);
                const modalEl = document.getElementById('generateFormModal');
                bootstrap.Modal.getOrCreateInstance(modalEl).hide();
                // reset form
                typeEl.value = '';
                reqEl.value = '';
                labelEl.value = '';
                optsEl.value = '';
                optsWrap.style.display = 'none';
            });
        })();
    </script>
@endpush