@extends('admin.layouts.auth')

@section('title', 'Admin | Dashboard')

@section('content')
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Admin Dashboard</div>
          <div class="card-body">
            <p class="mb-0">Welcome, {{ auth('admin')->user()->name }}!</p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
