@php
    $isSuperAdmin = (int) auth('admin')->id() === 1;

    $pendingDeposits = \App\Models\Deposit::where('status', 'pending')->count();
    $pendingWithdrawals = \App\Models\Withdrawal::where('status', 'pending')->count();
    $pendingOrderRequests = \App\Models\OrderRequest::where('status', 'pending')->count();

    $isUsersActive = request()->routeIs('admin.users.*');
    $isPlatformsActive = request()->routeIs('admin.platforms.*') || request()->routeIs('admin.platform-rule.*');
    $isOrdersActive = request()->routeIs('admin.order-sets.*') || request()->routeIs('admin.product-packages.*') || request()->routeIs('admin.orders.*');
    $isReportsActive = request()->routeIs('admin.transactions.*') || request()->routeIs('admin.reports.*');
    $isSettingsActive = request()->routeIs('admin.settings.*');
@endphp

<style>
    .deznav .menu-section-title {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #8a92a6;
        padding: 14px 18px 8px;
    }

    .deznav .metismenu>li>a {
        border-radius: 10px;
        margin: 2px 10px;
        padding: 10px 12px;
        transition: all .2s ease;
    }

    .deznav .metismenu>li>a i {
        font-size: 18px;
        min-width: 22px;
    }

    .deznav .metismenu>li:hover>a {
        background: #f3f7ff;
    }

    .deznav .metismenu>li.mm-active>a {
        background: linear-gradient(135deg, rgba(28, 152, 80, 0.15) 0%, rgba(28, 152, 80, 0.08) 100%);
        color: #1f2937;
        font-weight: 600;
    }

    .deznav .metismenu ul a {
        border-radius: 8px;
        margin: 1px 10px 1px 36px;
        padding: 8px 12px;
    }

    .deznav .metismenu ul li.mm-active>a,
    .deznav .metismenu ul a:hover {
        background: #f3f7ff;
        color: #1f2937;
    }

    .deznav .nav-text .badge {
        border-radius: 999px;
        font-size: 10px;
        padding: 3px 7px;
    }
</style>

<div class="deznav">
    <div class="deznav-scroll">
        <ul class="metismenu" id="menu">
            <li class="{{ request()->routeIs('admin.dashboard') ? 'mm-active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-381-menu"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            <li class="menu-section-title">Management</li>

            <li class="{{ $isUsersActive ? 'mm-active' : '' }}">
                <a href="javascript:void(0);" class="ai-icon has-arrow"
                    aria-expanded="{{ $isUsersActive ? 'true' : 'false' }}">
                    <i class="flaticon-381-id-card-4"></i>
                    <span class="nav-text">Users</span>
                </a>
                <ul aria-expanded="{{ $isUsersActive ? 'true' : 'false' }}" @if($isUsersActive) style="display:block;"
                @endif>
                    <li class="{{ request()->routeIs('admin.users.index') ? 'mm-active' : '' }}"><a
                            href="{{ route('admin.users.index') }}">All users</a></li>
                    @if($isSuperAdmin)
                        <li class="{{ request()->routeIs('admin.users.admins') ? 'mm-active' : '' }}"><a
                                href="{{ route('admin.users.admins') }}">Admin users</a></li>
                    @endif
                </ul>
            </li>

            <li class="{{ $isPlatformsActive ? 'mm-active' : '' }}">
                <a href="javascript:void(0);" class="ai-icon has-arrow"
                    aria-expanded="{{ $isPlatformsActive ? 'true' : 'false' }}">
                    <i class="flaticon-381-network"></i>
                    <span class="nav-text">Platforms</span>
                </a>
                <ul aria-expanded="{{ $isPlatformsActive ? 'true' : 'false' }}" @if($isPlatformsActive)
                style="display:block;" @endif>
                    <li class="{{ request()->routeIs('admin.platforms.*') ? 'mm-active' : '' }}"><a
                            href="{{ route('admin.platforms.index') }}">Platforms</a></li>
                    <li class="{{ request()->routeIs('admin.platform-rule.*') ? 'mm-active' : '' }}"><a
                            href="{{ route('admin.platform-rule.index') }}">Platform Rules</a></li>
                </ul>
            </li>

            <li class="{{ request()->routeIs('admin.deposits.*') ? 'mm-active' : '' }}">
                <a href="{{ route('admin.deposits.index') }}" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-381-calculator"></i>
                    <span class="nav-text">Deposits
                        @if($pendingDeposits > 0)
                            <span class="badge bg-danger badge-sm ms-2">{{ $pendingDeposits }}</span>
                        @endif
                    </span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.withdrawals.*') ? 'mm-active' : '' }}">
                <a href="{{ route('admin.withdrawals.index') }}" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-381-download"></i>
                    <span class="nav-text">Withdrawals
                        @if($pendingWithdrawals > 0)
                            <span class="badge bg-danger badge-sm ms-2">{{ $pendingWithdrawals }}</span>
                        @endif
                    </span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.order-requests.*') ? 'mm-active' : '' }}">
                <a href="{{ route('admin.order-requests.index') }}" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-381-file"></i>
                    <span class="nav-text">Order Requests
                        @if($pendingOrderRequests > 0)
                            <span class="badge bg-danger badge-sm ms-2">{{ $pendingOrderRequests }}</span>
                        @endif
                    </span>
                </a>
            </li>

            @if($isSuperAdmin)
                <li class="{{ request()->routeIs('admin.products.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.products.index') }}" class="ai-icon" aria-expanded="false">
                        <i class="flaticon-381-notepad"></i>
                        <span class="nav-text">Products</span>
                    </a>
                </li>
            @endif

            @if($isSuperAdmin)
                <li class="{{ $isOrdersActive ? 'mm-active' : '' }}">
                    <a href="javascript:void(0);" class="ai-icon has-arrow"
                        aria-expanded="{{ $isOrdersActive ? 'true' : 'false' }}">
                        <i class="flaticon-381-layer-1"></i>
                        <span class="nav-text">Orders</span>
                    </a>
                    <ul aria-expanded="{{ $isOrdersActive ? 'true' : 'false' }}" @if($isOrdersActive) style="display:block;"
                    @endif>
                        <li class="{{ request()->routeIs('admin.order-sets.*') ? 'mm-active' : '' }}"><a
                                href="{{ route('admin.order-sets.index') }}">Order Sets</a></li>
                        <li class="{{ request()->routeIs('admin.product-packages.*') ? 'mm-active' : '' }}"><a
                                href="{{ route('admin.product-packages.index') }}">Order List</a></li>
                        <li class="{{ request()->routeIs('admin.orders.*') ? 'mm-active' : '' }}"><a
                                href="{{ route('admin.orders.index') }}">Orders</a></li>
                    </ul>
                </li>
            @endif

            @if($isSuperAdmin)
                <li class="{{ request()->routeIs('admin.gateways.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.gateways.index') }}" class="ai-icon" aria-expanded="false">
                        <i class="flaticon-381-id-card-4"></i>
                        <span class="nav-text">Gateways</span>
                    </a>
                </li>
            @endif
            <li class="menu-section-title">Insights</li>

            <li class="{{ $isReportsActive ? 'mm-active' : '' }}">
                <a href="javascript:void(0);" class="ai-icon has-arrow"
                    aria-expanded="{{ $isReportsActive ? 'true' : 'false' }}">
                    <i class="flaticon-381-file"></i>
                    <span class="nav-text">Reports</span>
                </a>
                <ul aria-expanded="{{ $isReportsActive ? 'true' : 'false' }}" @if($isReportsActive)
                style="display:block;" @endif>
                    <li class="{{ request()->routeIs('admin.transactions.*') ? 'mm-active' : '' }}"><a
                            href="{{ route('admin.transactions.index') }}">Transaction Log</a></li>
                    <li class="{{ request()->routeIs('admin.reports.referral-commissions') ? 'mm-active' : '' }}"><a
                            href="{{ route('admin.reports.referral-commissions') }}">Referral Commission</a></li>
                    <li class="{{ request()->routeIs('admin.reports.login-history') ? 'mm-active' : '' }}"><a
                            href="{{ route('admin.reports.login-history') }}">Login History</a></li>
                    {{-- <li><a href="javascript:void(0)">Notification History</a></li> --}}
                </ul>
            </li>

            <li class="{{ $isSettingsActive ? 'mm-active' : '' }}">
                <a href="javascript:void(0);" class="ai-icon has-arrow"
                    aria-expanded="{{ $isSettingsActive ? 'true' : 'false' }}">
                    <i class="flaticon-381-settings"></i>
                    <span class="nav-text">Settings</span>
                </a>
                <ul aria-expanded="{{ $isSettingsActive ? 'true' : 'false' }}" @if($isSettingsActive)
                style="display:block;" @endif>
                    <li class="{{ request()->routeIs('admin.settings.index') ? 'mm-active' : '' }}"><a
                            href="{{ route('admin.settings.index') }}">General Settings</a></li>
                    <li class="{{ request()->routeIs('admin.settings.seo') ? 'mm-active' : '' }}"><a
                            href="{{ route('admin.settings.seo') }}">SEO Configuration</a></li>
                </ul>
            </li>
        </ul>

        {{-- <div class="plus-box">
            <p class="fs-16 font-w500 mb-1">Check your job schedule</p>
            <a class="text-white fs-26" href="javascript:;"><i class="las la-long-arrow-alt-right"></i>
            </a>
        </div> --}}
    </div>
</div>