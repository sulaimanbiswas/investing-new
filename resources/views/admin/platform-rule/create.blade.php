@extends('admin.layouts.app')

@section('title', 'Admin | Create Platform Rule')

@section('content')
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.platform-rule.index') }}">Platform Rules</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Create Rule</a></li>
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
                    <h4 class="card-title">Create Rule</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.platform-rule.store') }}">
                        @include('admin.platform-rule.form')
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('admin.platform-rule.index') }}" class="btn btn-secondary">Cancel</a>
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
            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            if (!tokenMeta) return;
            const token = tokenMeta.getAttribute('content');
            const dropzoneEl = document.getElementById('rule-image-dropzone');
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
                const imageInput = document.getElementById('rule_image');
                if (!imageInput) return;
                Dropzone.autoDiscover = false;
                const dz = new Dropzone('#rule-image-dropzone', {
                    url: '{{ url('/admin/uploads/qrs?folder=platform-rules') }}',
                    method: 'post',
                    headers: { 'X-CSRF-TOKEN': token },
                    maxFiles: 1,
                    maxFilesize: 5,
                    acceptedFiles: 'image/jpeg,image/png,image/webp,image/jpg',
                    addRemoveLinks: true,
                    dictDefaultMessage: 'Drag & drop image here or click to upload',
                });

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
            const el = document.querySelector('#rule_description');
            if (!el) return;
            ClassicEditor.create(el, {
                simpleUpload: {
                    uploadUrl: '{{ route('admin.ckeditor.upload') }}',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                }
            }).catch(console.error);
        })();
    </script>
@endpush