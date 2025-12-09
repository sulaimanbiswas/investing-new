@extends('admin.layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="profile card card-body px-3 pt-3 pb-0">
                <div class="profile-head">
                    <div class="photo-content">
                        <div class="cover-photo rounded"
                            style="background: linear-gradient(135deg, var(--primary) 0%, #667eea 100%); height: 200px;">
                        </div>
                    </div>
                    <div class="profile-info">
                        <div class="profile-photo">
                            @if(auth('admin')->user()->avatar)
                                <img src="{{ asset('uploads/avatar/' . auth('admin')->user()->avatar) }}"
                                    class="img-fluid rounded-circle" alt="Admin Avatar">
                            @else
                                <div class="img-fluid rounded-circle d-flex align-items-center justify-content-center bg-primary text-white"
                                    style="width: 100px; height: 100px;">
                                    <i class="fas fa-user" style="font-size: 50px;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="profile-details">
                            <div class="profile-name px-3 pt-2">
                                <h4 class="text-primary mb-0">{{ auth('admin')->user()->name }}</h4>
                                <p>Administrator</p>
                            </div>
                            <div class="profile-email px-2 pt-2">
                                <h4 class="text-muted mb-0">{{ auth('admin')->user()->email }}</h4>
                                <p>{{"@" . auth('admin')->user()->username }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-5">
            <div class="card">
                <div class="card-body">
                    <div class="profile-statistics">
                        <div class="text-center">
                            <div class="row">
                                <div class="col">
                                    <h3 class="m-b-0">{{ \App\Models\User::count() }}</h3>
                                    <span>Total Users</span>
                                </div>
                                <div class="col">
                                    <h3 class="m-b-0">{{ \App\Models\Platform::count() }}</h3>
                                    <span>Platforms</span>
                                </div>
                                <div class="col">
                                    <h3 class="m-b-0">{{ \App\Models\Product::count() }}</h3>
                                    <span>Products</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="profile-personal-info">
                        <h4 class="text-primary mb-4">Personal Information</h4>
                        <div class="row mb-2">
                            <div class="col-sm-4 col-5">
                                <h5 class="f-w-500">Name <span class="pull-end">:</span></h5>
                            </div>
                            <div class="col-sm-8 col-7">
                                <span>{{ auth('admin')->user()->name }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 col-5">
                                <h5 class="f-w-500">Email <span class="pull-end">:</span></h5>
                            </div>
                            <div class="col-sm-8 col-7">
                                <span>{{ auth('admin')->user()->email }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 col-5">
                                <h5 class="f-w-500">Username <span class="pull-end">:</span></h5>
                            </div>
                            <div class="col-sm-8 col-7">
                                <span>{{ auth('admin')->user()->username }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 col-5">
                                <h5 class="f-w-500">Joined <span class="pull-end">:</span></h5>
                            </div>
                            <div class="col-sm-8 col-7">
                                <span>{{ auth('admin')->user()->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 col-5">
                                <h5 class="f-w-500">Last Login <span class="pull-end">:</span></h5>
                            </div>
                            <div class="col-sm-8 col-7">
                                <span>{{ auth('admin')->user()->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-7">
            <div class="card h-auto">
                <div class="card-body">
                    <div class="profile-tab">
                        <div class="custom-tab-1">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a href="#about-me" data-bs-toggle="tab" class="nav-link active show">
                                        <i class="fas fa-user me-2"></i>About
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#profile-settings" data-bs-toggle="tab" class="nav-link">
                                        <i class="fas fa-cog me-2"></i>Settings
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div id="about-me" class="tab-pane fade active show">
                                    <div class="profile-about-me">
                                        <div class="pt-4 border-bottom-1 pb-3">
                                            <h4 class="text-primary mb-3">About Admin</h4>
                                            <p class="mb-2">
                                                Welcome to your admin dashboard. You have full access to manage the
                                                platform, users, orders, transactions, and all system settings.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="profile-personal-info mt-4">
                                        <h4 class="text-primary mb-4">Quick Stats</h4>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <div class="card bg-primary">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div>
                                                                <h2 class="text-white mb-0">
                                                                    {{ \App\Models\Deposit::where('status', 'approved')->count() }}
                                                                </h2>
                                                                <span class="text-white">Approved Deposits</span>
                                                            </div>
                                                            <i
                                                                class="fas fa-money-bill-wave fa-3x text-white opacity-50"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="card bg-success">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div>
                                                                <h2 class="text-white mb-0">
                                                                    {{ \App\Models\Withdrawal::where('status', 'approved')->count() }}
                                                                </h2>
                                                                <span class="text-white">Approved Withdrawals</span>
                                                            </div>
                                                            <i class="fas fa-wallet fa-3x text-white opacity-50"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="card bg-warning">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div>
                                                                <h2 class="text-white mb-0">
                                                                    {{ \App\Models\UserOrder::where('status', 'paid')->count() }}
                                                                </h2>
                                                                <span class="text-white">Completed Orders</span>
                                                            </div>
                                                            <i class="fas fa-check-circle fa-3x text-white opacity-50"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="card bg-info">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div>
                                                                <h2 class="text-white mb-0">
                                                                    {{ \App\Models\OrderSet::where('is_active', true)->count() }}
                                                                </h2>
                                                                <span class="text-white">Active Order Sets</span>
                                                            </div>
                                                            <i class="fas fa-boxes fa-3x text-white opacity-50"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="profile-settings" class="tab-pane fade">
                                    <div class="pt-3">
                                        <div class="settings-form">
                                            <h4 class="text-primary mb-4">Account Settings</h4>

                                            @if(session('success'))
                                                <div class="alert alert-success alert-dismissible fade show">
                                                    {{ session('success') }}
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                                </div>
                                            @endif

                                            @if($errors->any())
                                                <div class="alert alert-danger alert-dismissible fade show">
                                                    <ul class="mb-0">
                                                        @foreach($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                                </div>
                                            @endif

                                            <form method="POST" action="{{ route('admin.profile.update') }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Full Name <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="name"
                                                            value="{{ old('name', auth('admin')->user()->name) }}"
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            placeholder="Enter full name" required>
                                                        @error('name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Username <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="username"
                                                            value="{{ old('username', auth('admin')->user()->username) }}"
                                                            class="form-control @error('username') is-invalid @enderror"
                                                            placeholder="Enter username" required>
                                                        @error('username')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">Email <span
                                                                class="text-danger">*</span></label>
                                                        <input type="email" name="email"
                                                            value="{{ old('email', auth('admin')->user()->email) }}"
                                                            class="form-control @error('email') is-invalid @enderror"
                                                            placeholder="Enter email address" required>
                                                        @error('email')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">Profile Avatar</label>
                                                        <input type="file" name="avatar" accept="image/*"
                                                            class="form-control @error('avatar') is-invalid @enderror">
                                                        <small class="text-muted">Accepted: jpg, jpeg, png. Max
                                                            2MB.</small>
                                                        @error('avatar')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <hr class="my-4">

                                                <h4 class="text-primary mb-3">Change Password</h4>
                                                <p class="text-muted mb-3">Leave blank if you don't want to change
                                                    password</p>

                                                <div class="row">
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">Current Password</label>
                                                        <input type="password" name="current_password"
                                                            class="form-control @error('current_password') is-invalid @enderror"
                                                            placeholder="Enter current password">
                                                        @error('current_password')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">New Password</label>
                                                        <input type="password" name="password"
                                                            class="form-control @error('password') is-invalid @enderror"
                                                            placeholder="Enter new password">
                                                        @error('password')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Confirm Password</label>
                                                        <input type="password" name="password_confirmation"
                                                            class="form-control" placeholder="Confirm new password">
                                                    </div>
                                                </div>

                                                <div class="mt-4">
                                                    <button class="btn btn-primary" type="submit">
                                                        <i class="fas fa-save me-2"></i>Update Profile
                                                    </button>
                                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary ms-2">
                                                        <i class="fas fa-times me-2"></i>Cancel
                                                    </a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .profile .profile-photo {
            position: relative;
            margin-top: -50px;
        }

        .profile .profile-photo img,
        .profile .profile-photo>div {
            border: 5px solid #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .cover-photo {
            position: relative;
            overflow: hidden;
        }

        .profile-name h4 {
            font-size: 24px;
            font-weight: 700;
        }

        .profile-email h4 {
            font-size: 16px;
        }

        .card.bg-primary .opacity-50,
        .card.bg-success .opacity-50,
        .card.bg-warning .opacity-50,
        .card.bg-info .opacity-50 {
            opacity: 0.3;
        }
    </style>
@endpush