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
                    <!-- Mobile: Accordion toggle button -->
                    <div class="accordion accordion-header-bg accordion-bordered d-md-none mb-2"
                        id="platformFilterAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header accordion-header-primary" id="platformFilterHeading">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#platformFilterCollapse" aria-expanded="false"
                                    aria-controls="platformFilterCollapse">
                                    Filter
                                </button>
                            </h2>
                            <div id="platformFilterCollapse" class="accordion-collapse collapse"
                                aria-labelledby="platformFilterHeading" data-bs-parent="#platformFilterAccordion">
                                <div class="accordion-body">
                                    <form method="GET" class="row g-2">
                                        <div class="col-12">
                                            <input type="text" name="search" value="{{ request('search') }}"
                                                class="form-control form-control-sm" placeholder="Search by name">
                                        </div>
                                        <div class="col-12 d-grid">
                                            <button class="btn btn-secondary" type="submit">Filter</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Desktop/tablet: inline filter form -->
                    <form method="GET" class="row mb-3 g-2 d-none d-md-flex">
                        <div class="col-md-4">
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="form-control form-control-sm" placeholder="Search by name">
                        </div>
                        <div class="col-md-2 d-grid">
                            <button class="btn btn-secondary btn-sm" type="submit">Filter</button>
                        </div>
                    </form>

                    <div class="table-responsive recentOrderTable">
                        <table class="table verticle-middle table-responsive-md">
                            <thead>
                                <tr>
                                    <th>#</th>
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