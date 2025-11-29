@extends('admin.layouts.app')

@section('title', 'Admin | Platforms')

@section('content')
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Platforms</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Manage Platforms</a></li>
        </ol>
    </div>

    @if(session('status'))
        <div class="alert alert-success solid alert-dismissible fade show">
            <strong>Success!</strong> {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Platforms</h4>
                    <a href="{{ route('admin.platforms.create') }}" class="btn btn-primary btn-sm">Create</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-sm">
                            <thead>
                                <tr>
                                    <th style="width:80px">#</th>
                                    <th>Name</th>
                                    <th>Commission (%)</th>
                                    <th>Start Price</th>
                                    <th>End Price</th>

                                    <th>Total Products</th>
                                    <th class="text-end" style="width:120px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($platforms as $platform)
                                    <tr>
                                        <td><strong class="text-black">{{ $platform->id }}</strong></td>
                                        <td>@if($platform->image)
                                            <img src="{{ asset($platform->image) }}" alt="{{ $platform->name }}"
                                                style="height:32px;">
                                        @endif {{ $platform->name }}
                                        </td>
                                        <td>{{ rtrim(rtrim(number_format($platform->commission, 2, '.', ''), '0'), '.') }}</td>
                                        <td>{{ rtrim(rtrim(number_format($platform->start_price, 2, '.', ''), '0'), '.') }}</td>
                                        <td>{{ rtrim(rtrim(number_format($platform->end_price, 2, '.', ''), '0'), '.') }}</td>
                                        <td>{{ $platform->products_count }}</td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-success light sharp"
                                                    data-bs-toggle="dropdown">
                                                    <svg width="20" height="20" viewBox="0 0 24 24">
                                                        <circle cx="5" cy="12" r="2" />
                                                        <circle cx="12" cy="12" r="2" />
                                                        <circle cx="19" cy="12" r="2" />
                                                    </svg>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.platforms.edit', $platform) }}">Edit</a>
                                                    <form method="POST"
                                                        action="{{ route('admin.platforms.destroy', $platform) }}"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item"
                                                            onclick="return confirm('Delete this platform?')">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No platforms found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $platforms->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection