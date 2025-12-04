@extends('admin.layouts.app')

@section('title', 'Admin | User Tree - ' . $user->username)

@section('content')
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.show', $user) }}">{{ $user->username }}</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Referral Tree</a></li>
        </ol>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Referral Tree - {{ $user->username }}</h4>
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to User
                    </a>
                </div>
                <div class="card-body">
                    <div class="tree-container">
                        <div class="tree">
                            <!-- Root User -->
                            <ul>
                                <li>
                                    <a href="{{ route('admin.users.show', $user) }}" class="user-node root-node">
                                        @if($user->avatar_path)
                                            <img src="{{ asset('uploads/avatar/' . $user->avatar_path) }}" alt="{{ $user->username }}"
                                                class="user-avatar">
                                        @else
                                            <div class="user-avatar-placeholder">
                                                <i class="fas fa-user" style="font-size: 28px;"></i>
                                            </div>
                                        @endif
                                        <div class="user-info">
                                            <strong>{{ "@" . $user->username }}</strong>
                                            <span class="badge bg-primary">{{ $user->referrals->count() }}</span>
                                        </div>
                                    </a>

                                    @if($user->referrals->count() > 0)
                                        <ul>
                                            @foreach($user->referrals as $level1)
                                                <li>
                                                    <a href="{{ route('admin.users.show', $level1) }}" class="user-node level-1">
                                                        @if($level1->avatar_path)
                                                            <img src="{{ asset('uploads/avatar/' . $level1->avatar_path) }}"
                                                                alt="{{ $level1->username }}" class="user-avatar">
                                                        @else
                                                            <div class="user-avatar-placeholder">
                                                                <i class="fas fa-user" style="font-size: 28px;"></i>
                                                            </div>
                                                        @endif
                                                        <div class="user-info">
                                                            <strong>{{"@" . $level1->username ?? 'No Username' }}</strong>
                                                        </div>
                                                    </a>

                                                    @if($level1->referrals->count() > 0)
                                                        <ul>
                                                            @foreach($level1->referrals as $level2)
                                                                <li>
                                                                    <a href="{{ route('admin.users.show', $level2) }}"
                                                                        class="user-node level-2">
                                                                        @if($level2->avatar_path)
                                                                            <img src="{{ asset('uploads/avatar/' . $level2->avatar_path) }}"
                                                                                alt="{{ $level2->username }}" class="user-avatar">
                                                                        @else
                                                                            <div class="user-avatar-placeholder">
                                                                                <i class="fas fa-user" style="font-size: 28px;"></i>
                                                                            </div>
                                                                        @endif
                                                                        <div class="user-info">
                                                                            <strong>{{ "@" . ($level2->username ?? 'No Username') }}</strong>
                                                                        </div>
                                                                    </a>

                                                                    @if($level2->referrals->count() > 0)
                                                                        <ul>
                                                                            @foreach($level2->referrals as $level3)
                                                                                <li>
                                                                                    <a href="{{ route('admin.users.show', $level3) }}"
                                                                                        class="user-node level-3">
                                                                                        @if($level3->avatar_path)
                                                                                            <img src="{{ asset('uploads/avatar/' . $level3->avatar_path) }}"
                                                                                                alt="{{ $level3->username }}" class="user-avatar">
                                                                                        @else
                                                                                            <div class="user-avatar-placeholder">
                                                                                                <i class="fas fa-user" style="font-size: 28px;"></i>
                                                                                            </div>
                                                                                        @endif
                                                                                        <div class="user-info">
                                                                                            <strong>{{ "@" . ($level3->username ?? 'No Username')
                                                                                                                                                        }}</strong>
                                                                                        </div>
                                                                                    </a>

                                                                                    @if($level3->referrals->count() > 0)
                                                                                        <ul>
                                                                                            @foreach($level3->referrals as $level4)
                                                                                                <li>
                                                                                                    <a href="{{ route('admin.users.show', $level4) }}"
                                                                                                        class="user-node level-4">
                                                                                                        @if($level4->avatar_path)
                                                                                                            <img src="{{ asset('uploads/avatar/' . $level4->avatar_path) }}"
                                                                                                                alt="{{ $level4->username }}"
                                                                                                                class="user-avatar">
                                                                                                        @else
                                                                                                            <div class="user-avatar-placeholder">
                                                                                                                <i class="fas fa-user" style="font-size: 28px;"></i>
                                                                                                            </div>
                                                                                                        @endif
                                                                                                        <div class="user-info">
                                                                                                            <strong>{{ "@" . ($level4->username ?? 'No Username') }}</strong>

                                                                                                        </div>
                                                                                                    </a>
                                                                                                </li>
                                                                                            @endforeach
                                                                                        </ul>
                                                                                    @endif
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            </ul>
                        </div>

                        @if($user->referrals->count() == 0)
                            <div class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Referrals Yet</h5>
                                <p class="text-muted">This user hasn't referred anyone yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .tree-container {
            overflow-x: auto;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 400px;
        }

        .tree {
            text-align: center;
            position: relative;
            display: inline-block;
        }

        .tree ul {
            padding-top: 20px;
            position: relative;
            transition: all 0.5s;
            list-style-type: none;
            padding-left: 0;
        }

        .tree li {
            float: left;
            text-align: center;
            list-style-type: none;
            position: relative;
            padding: 20px 5px 0 5px;
            transition: all 0.5s;
        }

        .tree li::before,
        .tree li::after {
            content: '';
            position: absolute;
            top: 0;
            right: 50%;
            border-top: 2px solid #ccc;
            width: 50%;
            height: 20px;
        }

        .tree li::after {
            right: auto;
            left: 50%;
            border-left: 2px solid #ccc;
        }

        .tree li:only-child::after,
        .tree li:only-child::before {
            display: none;
        }

        .tree li:only-child {
            padding-top: 0;
        }

        .tree li:first-child::before,
        .tree li:last-child::after {
            border: 0 none;
        }

        .tree li:last-child::before {
            border-right: 2px solid #ccc;
            border-radius: 0 5px 0 0;
        }

        .tree li:first-child::after {
            border-radius: 5px 0 0 0;
        }

        .tree ul ul::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            border-left: 2px solid #ccc;
            width: 0;
            height: 20px;
        }

        .tree li .user-node {
            border: 2px solid #6c757d;
            padding: 10px;
            text-decoration: none;
            color: #333;
            font-family: arial, verdana, tahoma;
            font-size: 14px;
            display: inline-block;
            border-radius: 10px;
            transition: all 0.3s;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            min-width: 150px;
        }

        .tree li .user-node:hover {
            background: #f8f9fa;
            border-color: #007bff;
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .tree li .user-node.root-node {
            border-color: #007bff;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
        }

        .tree li .user-node.root-node:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        .tree li .user-node.level-1 {
            border-color: #28a745;
        }

        .tree li .user-node.level-2 {
            border-color: #ffc107;
        }

        .tree li .user-node.level-3 {
            border-color: #17a2b8;
        }

        .tree li .user-node.level-4 {
            border-color: #dc3545;
        }

        .user-avatar,
        .user-avatar-placeholder {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin: 0 auto 10px;
            display: block;
            object-fit: cover;
        }

        .user-avatar-placeholder {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
        }

        .user-info {
            margin-top: 5px;
        }

        .user-info strong {
            display: block;
            margin-bottom: 5px;
            font-size: 11px;
            word-break: break-word;
        }

        .user-info .badge {
            font-size: 11px;
        }

        .root-node .user-info strong,
        .root-node .user-info .badge {
            color: #fff;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .tree li {
                padding: 15px 3px 0 3px;
            }

            .tree li .user-node {
                min-width: 120px;
                padding: 8px;
            }

            .user-avatar,
            .user-avatar-placeholder {
                width: 50px;
                height: 50px;
            }

            .user-info strong {
                font-size: 11px;
            }
        }
    </style>
@endpush