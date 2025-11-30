@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Edit Order Set</h4>
                        <a href="{{ route('admin.order-sets.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.order-sets.update', $orderSet->id) }}" method="POST">
                            @method('PUT')
                            @include('admin.order-sets.form', ['orderSet' => $orderSet])
                            <button class="btn btn-primary" type="submit">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection