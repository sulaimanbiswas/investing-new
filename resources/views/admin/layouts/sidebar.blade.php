<div class="deznav">
    <div class="deznav-scroll">
        <ul class="metismenu" id="menu">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-381-menu"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                    <i class="flaticon-381-id-card-4"></i>
                    <span class="nav-text">Users</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('admin.users.index') }}">All users</a></li>
                </ul>
            </li>
            <li>
                <a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                    <i class="flaticon-381-network"></i>
                    <span class="nav-text">Platforms</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('admin.platforms.index') }}">Platforms</a></li>
                    <li><a href="{{ route('admin.platform-rule.index') }}">Platform Rules</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ route('admin.deposits.index') }}" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-381-calculator"></i>
                    <span class="nav-text">Deposits
                        @php
                            $pendingDeposits = \App\Models\Deposit::where('status', 'pending')->count();
                        @endphp
                        @if($pendingDeposits > 0)
                            <span class="badge bg-danger badge-sm ms-2">{{ $pendingDeposits }}</span>
                        @endif
                    </span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.withdrawals.index') }}" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-381-download"></i>
                    <span class="nav-text">Withdrawals
                        @php
                            $pendingWithdrawals = \App\Models\Withdrawal::where('status', 'pending')->count();
                        @endphp
                        @if($pendingWithdrawals > 0)
                            <span class="badge bg-danger badge-sm ms-2">{{ $pendingWithdrawals }}</span>
                        @endif
                    </span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.products.index') }}" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-381-notepad"></i>
                    <span class="nav-text">Products</span>
                </a>
            </li>

            <li>
                <a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                    <i class="flaticon-381-layer-1"></i>
                    <span class="nav-text">Orders</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('admin.order-sets.index') }}">Order Sets</a></li>
                    <li><a href="{{ route('admin.product-packages.index') }}">Order List</a></li>
                    <li><a href="{{ route('admin.orders.index') }}">Orders</a></li>
                </ul>
            </li>

            <li>
                <a href="{{ route('admin.gateways.index') }}" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-381-id-card-4"></i>
                    <span class="nav-text">Gateways</span>
                </a>
            </li>


            <li>
                <a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                    <i class="flaticon-381-file"></i>
                    <span class="nav-text">Report</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('admin.transactions.index') }}">Transaction Log</a></li>
                    <li><a href="{{ route('admin.reports.referral-commissions') }}">Referral Commission</a></li>
                    <li><a href="{{ route('admin.reports.login-history') }}">Login History</a></li>
                    {{-- <li><a href="javascript:void(0)">Notification History</a></li> --}}
                </ul>
            </li>

            <li>
                <a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                    <i class="flaticon-381-settings"></i>
                    <span class="nav-text">Settings</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('admin.settings.index') }}">General Settings</a></li>
                    <li><a href="{{ route('admin.settings.seo') }}">SEO Configuration</a></li>
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