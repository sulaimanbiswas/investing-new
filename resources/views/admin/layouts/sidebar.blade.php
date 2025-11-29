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
                    <li><a href="{{ route('admin.users.active') }}">Active Users</a></li>
                    <li><a href="{{ route('admin.users.banned') }}">Banned Users</a></li>
                    <li><a href="{{ route('admin.users.index') }}">All users</a></li>
                </ul>
            </li>
            <li>
                <a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                    <i class="flaticon-381-id-card-4"></i>
                    <span class="nav-text">Gateways</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('admin.gateways.index') }}">Manage Gateways</a></li>
                    <li><a href="{{ route('admin.gateways.create') }}">Create Gateway</a></li>
                </ul>
            </li>
        </ul>

        <div class="plus-box">
            <p class="fs-16 font-w500 mb-1">Check your job schedule</p>
            <a class="text-white fs-26" href="javascript:;"><i class="las la-long-arrow-alt-right"></i>
            </a>
        </div>
    </div>
</div>