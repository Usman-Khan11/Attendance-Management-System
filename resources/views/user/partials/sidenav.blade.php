<aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu bg-menu-theme flex-grow-0">
    <div class="container-xxl d-flex h-100">
        <ul class="menu-inner">
            <li class="menu-item {{ menuActive('user.home') }}">
                <a href="{{ route('user.home') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-smart-home"></i>
                    <div data-i18n="Dashboard">Dashboard</div>
                </a>
            </li>

            <li class="menu-item {{ menuActive('user.public_holidays') }}">
                <a href="{{ route('user.public_holidays') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-ticket"></i>
                    <div data-i18n="Public Holidays">Public Holidays</div>
                </a>
            </li>

            <li class="menu-item {{ menuActive('user.leaves') }}">
                <a href="{{ route('user.leaves') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-ticket"></i>
                    <div data-i18n="My Leaves">My Leaves</div>
                </a>
            </li>
        </ul>
    </div>
</aside>