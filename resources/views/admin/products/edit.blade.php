@extends('admin.layouts.app')

@section('title', 'Admin | Edit Product')

@section('content')
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Product</a></li>
        </ol>
    </div>

    @if($errors->any())
        <div class="alert alert-danger solid alert-dismissible fade show">
            <strong>Validation Failed</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
            <ul class="mt-2 mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Product</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.products.update', $product) }}">
                        @method('PUT')
                        @include('admin.products.form')
                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            if (!tokenMeta) return;
            const token = tokenMeta.getAttribute('content');
            const dropzoneEl = document.getElementById('product-image-dropzone');
            if (!dropzoneEl) return;

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
                const imageInput = document.getElementById('product_image');
                if (!imageInput) return;
                Dropzone.autoDiscover = false;
                const dz = new Dropzone('#product-image-dropzone', {
                    url: '{{ url('/admin/uploads/qrs?folder=products') }}',
                    method: 'post',
                    headers: { 'X-CSRF-TOKEN': token },
                    maxFiles: 1,
                    maxFilesize: 5,
                    acceptedFiles: 'image/jpeg,image/png,image/webp,image/jpg',
                    addRemoveLinks: true,
                    dictDefaultMessage: 'Drag & drop image here or click to upload',
                });

                // Preload existing image if present
                const existingPath = (imageInput.value || '').trim();
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
                        imageInput.value = response.path;
                    } else if (response && response.url) {
                        imageInput.value = response.url;
                    }
                });

                dz.on('removedfile', function () {
                    const path = imageInput.value;
                    if (!path) return;
                    fetch('{{ url('/admin/uploads/qrs/delete') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ path })
                    }).then(() => {
                        imageInput.value = '';
                    }).catch(() => { });
                });
            });
        })();
    </script>
    <script>
        (function () {
            if (!window.ClassicEditor) return;
            const el = document.querySelector('#product_description');
            if (!el) return;

            ClassicEditor
                .create(el, {
                    simpleUpload: {
                        uploadUrl: '{{ route('admin.ckeditor.upload') }}',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    }
                })
                .catch(function (error) {
                    console.error(error);
                });
        })();
    </script>
@endpush