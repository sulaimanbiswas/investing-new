@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Create Product Package</h4>
                        <a href="{{ route('admin.product-packages.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.product-packages.store') }}" method="POST" id="productPackageForm">
                            @csrf
                            @include('admin.product-packages.form')
                            <button class="btn btn-primary" type="submit">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection