<aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu bg-menu-theme flex-grow-0">
    <div class="container-xxl d-flex h-100">
        <ul class="menu-inner">
            <li class="menu-item {{ menuActive('admin.home') }}">
                <a href="{{ route('admin.home') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-smart-home"></i>
                    <div data-i18n="Dashboard">Dashboard</div>
                </a>
            </li>

            <li class="menu-item {{ menuActive('admin.user*') }}">
                <a href="{{ route('admin.user') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-user"></i>
                    <div data-i18n="Users">Users</div>
                </a>
            </li>

            <li class="menu-item {{ menuActive('admin.public_holiday*') }}">
                <a href="{{ route('admin.public_holiday') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-ticket"></i>
                    <div data-i18n="Public Hoildays">Public Hoildays</div>
                </a>
            </li>

            <li class="menu-item {{ menuActive('admin.leave*') }}">
                <a href="{{ route('admin.leave') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-ticket"></i>
                    <div data-i18n="Leaves">Leaves</div>
                </a>
            </li>

            <li class="menu-item {{ menuActive('admin.attendence.summary') }}">
                <a href="{{ route('admin.attendence.summary') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-file"></i>
                    <div data-i18n="Summary">Summary</div>
                </a>
            </li>
        </ul>
    </div>
</aside>