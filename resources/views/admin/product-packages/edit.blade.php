@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Edit Product Package</h4>
                        <a href="{{ route('admin.product-packages.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.product-packages.update', $productPackage->id) }}" method="POST"
                            id="productPackageForm">
                            @csrf
                            @method('PUT')
                            @if(request('order_set_id'))
                                <input type="hidden" name="redirect_to_order_set" value="1">
                            @endif
                            @include('admin.product-packages.form', ['productPackage' => $productPackage])
                            <button class="btn btn-primary" type="submit">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection