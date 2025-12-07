@extends('admin.layouts.app')

@section('title', 'General Settings')

@section('content')
    <div class="page-titles mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Settings</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">General</a></li>
        </ol>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <h4>Validation Errors:</h4>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0">General Settings</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.settings.update') }}" class="needs-validation">
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
                                    <div class="col-md-6 mb-3">
                                        <label for="site_title" class="form-label">Site Title <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('site_title') is-invalid @enderror"
                                            id="site_title" name="site_title"
                                            value="{{ old('site_title', $data['site_title']) }}"
                                            placeholder="Enter site title" required>
                                        <small class="text-muted d-block mt-1">Used in page headers and SEO</small>
                                        @error('site_title')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="timezone" class="form-label">Timezone</label>
                                        <select name="timezone" id="timezone" class="default-select form-control wide"
                                            required>
                                            @foreach($timezones as $tz)
                                                <option value="{{ $tz }}" {{ old('timezone', $data['timezone']) === $tz ? 'selected' : '' }}>
                                                    {{ $tz }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Currency & Limits -->
                    <div class="col-12 mb-4">
                        <div class="card border">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0 text-white">Currency & Limits</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">


                                    <div class="col-md-4 mb-3">
                                        <label for="currency" class="form-label">Currency</label>
                                        <select name="currency" id="currency" class="default-select form-control wide"
                                            required>
                                            @foreach($currencies as $curr)
                                                <option value="{{ $curr }}" {{ old('currency', $data['currency']) === $curr ? 'selected' : '' }}>
                                                    {{ $curr }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- <div class="col-md-4 mb-3">
                                        <label for="currency" class="form-label">Currency <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('currency') is-invalid @enderror" id="currency"
                                            name="currency" required>
                                            <option value="">Select Currency</option>
                                            @foreach($currencies as $curr)
                                            <option value="{{ $curr }}" {{ old('currency', $data['currency'])===$curr
                                                ? 'selected' : '' }}>
                                                {{ $curr }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('currency')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div> --}}

                                    <div class="col-md-4 mb-3">
                                        <label for="currency_symbol" class="form-label">Currency Symbol <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('currency_symbol') is-invalid @enderror"
                                            id="currency_symbol" name="currency_symbol"
                                            value="{{ old('currency_symbol', $data['currency_symbol']) }}"
                                            placeholder="e.g., $, €, ₹" maxlength="10" required>
                                        <small class="text-muted d-block mt-1">Shown beside monetary values across the
                                            site</small>
                                        @error('currency_symbol')<span
                                        class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="daily_order_limit" class="form-label">Daily Order Limit <span
                                                class="text-danger">*</span></label>
                                        <input type="number"
                                            class="form-control @error('daily_order_limit') is-invalid @enderror"
                                            id="daily_order_limit" name="daily_order_limit"
                                            value="{{ old('daily_order_limit', $data['daily_order_limit']) }}"
                                            placeholder="25" min="1" required>
                                        <small class="text-muted d-block mt-1">Default limit per user per day</small>
                                        @error('daily_order_limit')<span
                                        class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Support & Help Links -->
                    <div class="col-12 mb-4">
                        <div class="card border">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0 text-white">Support & Help Links</h5>
                            </div>
                            <div class="card-body">
                                <p class="small text-muted mb-3">These links appear on the user help/service pages</p>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="telegram_support_link" class="form-label">Telegram Support Link</label>
                                        <input type="url"
                                            class="form-control @error('telegram_support_link') is-invalid @enderror"
                                            id="telegram_support_link" name="telegram_support_link"
                                            value="{{ old('telegram_support_link', $data['telegram_support_link']) }}"
                                            placeholder="https://t.me/...">
                                        @error('telegram_support_link')<span
                                        class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="whatsapp_support_link" class="form-label">WhatsApp Support Link</label>
                                        <input type="url"
                                            class="form-control @error('whatsapp_support_link') is-invalid @enderror"
                                            id="whatsapp_support_link" name="whatsapp_support_link"
                                            value="{{ old('whatsapp_support_link', $data['whatsapp_support_link']) }}"
                                            placeholder="https://wa.me/...">
                                        @error('whatsapp_support_link')<span
                                        class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="help_email" class="form-label">Help Email</label>
                                        <input type="email" class="form-control @error('help_email') is-invalid @enderror"
                                            id="help_email" name="help_email"
                                            value="{{ old('help_email', $data['help_email']) }}"
                                            placeholder="support@example.com">
                                        @error('help_email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="help_link" class="form-label">Help Link</label>
                                        <input type="url" class="form-control @error('help_link') is-invalid @enderror"
                                            id="help_link" name="help_link"
                                            value="{{ old('help_link', $data['help_link']) }}"
                                            placeholder="https://help.example.com">
                                        @error('help_link')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label for="help_description" class="form-label">Help Description</label>
                                        <textarea class="form-control @error('help_description') is-invalid @enderror"
                                            id="help_description" name="help_description" rows="3"
                                            placeholder="Help center description text">{{ old('help_description', $data['help_description']) }}</textarea>
                                        @error('help_description')<span
                                        class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Security & Features -->
                    <div class="col-12 mb-4">
                        <div class="card border">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0 text-white">Security & Features</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="enable_captcha"
                                        name="enable_captcha" value="1" {{ old('enable_captcha', $data['enable_captcha']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="enable_captcha">
                                        Enable Captcha Protection
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-2">Enable CAPTCHA on login and registration
                                    forms</small>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications -->
                    <div class="col-12 mb-4">
                        <div class="card border">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0 text-white">Notifications</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label for="notify_heading" class="form-label">Notification Heading</label>
                                        <input type="text"
                                            class="form-control @error('notify_heading') is-invalid @enderror"
                                            id="notify_heading" name="notify_heading"
                                            value="{{ old('notify_heading', $data['notify_heading']) }}"
                                            placeholder="Notification title" maxlength="255">
                                        @error('notify_heading')<span
                                        class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label for="notify_text" class="form-label">Notification Text</label>
                                        <textarea class="form-control @error('notify_text') is-invalid @enderror"
                                            id="notify_text" name="notify_text" rows="3"
                                            placeholder="Notification message">{{ old('notify_text', $data['notify_text']) }}</textarea>
                                        <small class="text-muted d-block mt-1">Displayed as system notification to
                                            users</small>
                                        @error('notify_text')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Logo & Favicon -->
                    <div class="col-12 mb-4">
                        <div class="card border">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0 text-white">Logo & Favicon</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning border-left-4 mb-4">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Note:</strong> If the logo and favicon are not changed after you update from
                                    this page, please clear the cache from your browser. As we keep the filename the same
                                    after the update, it may show the old image for the cache. usually, it works after clear
                                    the cache but if you still see the old logo or favicon, it may be caused by server level
                                    or network level caching. Please clear them too.
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Logo</label>
                                        <input type="hidden" id="logo_path" name="logo_path"
                                            value="{{ old('logo_path', $data['logo_path'] ?? '') }}">
                                        <div class="mb-3">
                                            @if($data['logo_path'] ?? false)
                                                <div class="border rounded p-3 bg-light text-center mb-3">
                                                    <img src="{{ asset($data['logo_path']) }}" alt="Logo" class="img-fluid"
                                                        style="max-height: 150px;">
                                                </div>
                                            @endif
                                            <div id="logo-dropzone" class="dropzone border rounded p-4 text-center"
                                                style="min-height: 200px;">
                                                <div class="dz-message">
                                                    <i class="fas fa-cloud-upload-alt text-primary"
                                                        style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
                                                    <p class="mb-0">Drag & drop logo here or click to upload</p>
                                                    <small class="text-muted">Accepted: jpg, jpeg, png, webp. Max
                                                        5MB.</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Favicon</label>
                                        <input type="hidden" id="favicon_path" name="favicon_path"
                                            value="{{ old('favicon_path', $data['favicon_path'] ?? '') }}">
                                        <div class="mb-3">
                                            @if($data['favicon_path'] ?? false)
                                                <div class="border rounded p-3 bg-light text-center mb-3">
                                                    <img src="{{ asset($data['favicon_path']) }}" alt="Favicon"
                                                        class="img-fluid" style="max-height: 100px;">
                                                </div>
                                            @endif
                                            <div id="favicon-dropzone" class="dropzone border rounded p-4 text-center"
                                                style="min-height: 200px;">
                                                <div class="dz-message">
                                                    <i class="fas fa-cloud-upload-alt text-primary"
                                                        style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
                                                    <p class="mb-0">Drag & drop favicon here or click to upload</p>
                                                    <small class="text-muted">Accepted: ico, png. Max 1MB.</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Settings
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary ms-2">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/dropzone.min.css">
    <script src="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/dropzone.min.js"></script>

    <script>
        // Logo Dropzone
        Dropzone.options.logoDropzone = {
            url: '{{ route("admin.uploads.logo") }}',
            paramName: 'logo',
            maxFilesize: 5, // MB
            maxFiles: 1,
            acceptedFiles: '.jpg,.jpeg,.png,.webp',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            dictDefaultMessage: 'Drag & drop logo here or click to upload',
            dictRemoveFile: 'Remove',
            init: function () {
                this.on('success', function (file, response) {
                    if (response.path) {
                        document.getElementById('logo_path').value = response.path;
                    }
                });
            }
        };

        // Favicon Dropzone
        Dropzone.options.faviconDropzone = {
            url: '{{ route("admin.uploads.favicon") }}',
            paramName: 'favicon',
            maxFilesize: 1, // MB
            maxFiles: 1,
            acceptedFiles: '.ico,.png',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            dictDefaultMessage: 'Drag & drop favicon here or click to upload',
            dictRemoveFile: 'Remove',
            init: function () {
                this.on('success', function (file, response) {
                    if (response.path) {
                        document.getElementById('favicon_path').value = response.path;
                    }
                });
            }
        };
    </script>
@endpush