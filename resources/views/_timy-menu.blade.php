<li class="nav-item dropdown">
    <a id="timyNavDropDown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ __('timy::titles.menu_title') }}</span>
    </a>

    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="timyNavDropDown">
        @can('timy-user')
            <a class="dropdown-item" href="{{ route('user_dashboard') }}" class="nav-link">{{ __('timy::titles.user') }}</a>
        @endcan
        @can('timy-admin')
            <a class="dropdown-item" href="{{ route('admin_dashboard') }}" class="nav-link">{{ __('timy::titles.admin') }}</a>
        @endcan
        @can('timy-super-admin')
            <a class="dropdown-item" href="{{ route('super_admin_dashboard') }}" class="nav-link">{{ __('timy::titles.super_admin') }}</a>
        @endcan
    </div>
</li>
<li class="nav-item">
    @can('timy-user')
        @livewire('timy::timer-control')
    @endcan
</li>