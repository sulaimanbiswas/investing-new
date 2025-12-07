@extends('admin.layouts.app')

@section('title', 'SEO Configuration')

@section('content')
    <div class="page-titles mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Settings</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">SEO Configuration</a></li>
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
            <h4 class="card-title mb-0">SEO Configuration</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.settings.update-seo') }}" class="needs-validation">
                @csrf

                <div class="row">
                    <!-- OG Image -->
                    <div class="col-12 mb-4">
                        <div class="card border">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0 text-white">Default OG Image</h5>
                            </div>
                            <div class="card-body">
                                <p class="small text-muted mb-3">This image is used as the default Open Graph image when a
                                    page is shared on social media. It should be at least 1200x630px for best results.</p>
                                <input type="hidden" id="og_image_path" name="og_image_path"
                                    value="{{ old('og_image_path', $data['og_image_path'] ?? '') }}">
                                <div class="mb-3">
                                    @if($data['og_image_path'] ?? false)
                                        <div class="border rounded p-3 bg-light text-center mb-3">
                                            <img src="{{ asset($data['og_image_path']) }}" alt="OG Image" class="img-fluid"
                                                style="max-height: 200px;">
                                        </div>
                                    @endif
                                    <div id="og-image-dropzone" class="dropzone border rounded p-4 text-center"
                                        style="min-height: 200px;">
                                        <div class="dz-message">
                                            <i class="fas fa-cloud-upload-alt text-primary"
                                                style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
                                            <p class="mb-0">Drag & drop OG image here or click to upload</p>
                                            <small class="text-muted">Accepted: jpg, jpeg, png, webp. Max 5MB. Recommended:
                                                1200x630px</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Meta Description -->
                    <div class="col-12 mb-4">
                        <div class="card border">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0 text-white">Meta Description</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label for="meta_description" class="form-label">Meta Description</label>
                                        <textarea class="form-control @error('meta_description') is-invalid @enderror"
                                            id="meta_description" name="meta_description" rows="3"
                                            placeholder="A brief description of your website for search engines"
                                            maxlength="160">{{ old('meta_description', $data['meta_description']) }}</textarea>
                                        <small class="text-muted d-block mt-1">
                                            <span
                                                id="meta-desc-count">{{ strlen(old('meta_description', $data['meta_description'])) }}</span>/160
                                            characters
                                        </small>
                                        <small class="text-muted d-block">This text appears under your website title in
                                            search results</small>
                                        @error('meta_description')<span
                                        class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Meta Keywords -->
                    <div class="col-12 mb-4">
                        <div class="card border">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0 text-white">Meta Keywords</h5>
                            </div>
                            <div class="card-body">
                                <p class="small text-muted mb-3">Add keywords separated by commas. You can add multiple
                                    keywords using the input field.</p>
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label for="meta_keywords" class="form-label">Keywords</label>
                                        <select id="meta_keywords" name="meta_keywords" class="form-select select2-multiple"
                                            multiple="multiple" style="width: 100%;">
                                            @php
                                                $keywords = old('meta_keywords', $data['meta_keywords']);
                                                if (is_string($keywords)) {
                                                    $keywords = array_filter(array_map('trim', explode(',', $keywords)));
                                                }
                                            @endphp
                                            @foreach($keywords as $keyword)
                                                <option value="{{ $keyword }}" selected>{{ $keyword }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted d-block mt-1">Type a keyword and press Enter or Tab to add
                                            it</small>
                                        @error('meta_keywords')<span
                                        class="invalid-feedback d-block">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Title & Description -->
                    <div class="col-12 mb-4">
                        <div class="card border">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0 text-white">Social Media Sharing</h5>
                            </div>
                            <div class="card-body">
                                <p class="small text-muted mb-3">Configure how your website appears when shared on social
                                    media platforms like Facebook and Twitter</p>
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label for="social_title" class="form-label">Social Title</label>
                                        <input type="text" class="form-control @error('social_title') is-invalid @enderror"
                                            id="social_title" name="social_title"
                                            value="{{ old('social_title', $data['social_title']) }}"
                                            placeholder="How you want your site to appear when shared" maxlength="255">
                                        <small class="text-muted d-block mt-1">Used as the title when your site is shared on
                                            social networks</small>
                                        @error('social_title')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label for="social_description" class="form-label">Social Description</label>
                                        <textarea class="form-control @error('social_description') is-invalid @enderror"
                                            id="social_description" name="social_description" rows="3"
                                            placeholder="Description that appears when shared on social media"
                                            maxlength="200">{{ old('social_description', $data['social_description']) }}</textarea>
                                        <small class="text-muted d-block mt-1">
                                            <span
                                                id="social-desc-count">{{ strlen(old('social_description', $data['social_description'])) }}</span>/200
                                            characters
                                        </small>
                                        <small class="text-muted d-block">Brief description displayed alongside the title on
                                            social platforms</small>
                                        @error('social_description')<span
                                        class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save SEO Settings
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/dropzone.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // OG Image Dropzone
        Dropzone.options.ogImageDropzone = {
            url: '{{ route("admin.uploads.og-image") }}',
            paramName: 'og_image',
            maxFilesize: 5, // MB
            maxFiles: 1,
            acceptedFiles: '.jpg,.jpeg,.png,.webp',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            dictDefaultMessage: 'Drag & drop OG image here or click to upload',
            dictRemoveFile: 'Remove',
            init: function () {
                this.on('success', function (file, response) {
                    if (response.path) {
                        document.getElementById('og_image_path').value = response.path;
                    }
                });
            }
        };

        // Meta Keywords Select2
        $('#meta_keywords').select2({
            tags: true,
            tokenSeparators: [','],
            placeholder: 'Type keywords and press Enter',
            allowClear: true,
            data: function () {
                var existing = document.getElementById('meta_keywords').value || [];
                if (typeof existing === 'string') {
                    existing = existing.split(',').map(k => k.trim());
                }
                return existing;
            }
        });

        // Meta Description character counter
        document.getElementById('meta_description')?.addEventListener('input', function () {
            document.getElementById('meta-desc-count').textContent = this.value.length;
        });

        // Social Description character counter
        document.getElementById('social_description')?.addEventListener('input', function () {
            document.getElementById('social-desc-count').textContent = this.value.length;
        });

        // Handle form submission for Select2
        document.querySelector('form').addEventListener('submit', function () {
            var keywords = $('#meta_keywords').val();
            if (keywords && Array.isArray(keywords)) {
                // Convert array to comma-separated string for submission
                document.getElementById('meta_keywords').value = keywords.join(', ');
            }
        });
    </script>
@endpush