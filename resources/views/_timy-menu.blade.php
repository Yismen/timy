<li class="nav-item dropdown">
    <a id="timyNavDropDown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
        Timy Dasoboards</span>
    </a>

    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="timyNavDropDown">
        @can('timy-user')
            <a class="dropdown-item" href="{{ route('user_dashboard') }}" class="nav-link">User</a>
        @endcan
        @can('timy-admin')
            <a class="dropdown-item" href="{{ route('admin_dashboard') }}" class="nav-link">Timy Admin</a>
        @endcan
        @can('timy-super-admin')
            <a class="dropdown-item" href="{{ route('super_admin_dashboard') }}" class="nav-link">Timy Super Admin</a>
        @endcan
    </div>
</li>
<li class="nav-item">
    @can('timy-user')
        <timy-timers-control></timy-timers-control>
    @endcan
</li>