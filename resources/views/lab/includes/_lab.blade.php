<li class="nav-header">Order Management</li>
<li class="nav-item">
    <a href="#" class="nav-link  {{ preg_match('/\/lab\//i', request()->url()) ? 'active' : null }}">
        <i class="nav-icon fas fa-chart-pie"></i>
        <p>
            lab
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview" style="display: none;">
        @if (Auth::user()->can('test-type-view'))
            <li class="nav-item {{ request()->routeIs('test-type*') ? 'active' : null }}">
                <a href="{{ route('test-type.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Test Type</p>
                </a>
            </li>
        @endif
        @if (Auth::user()->can('patient-view'))
            <li class="nav-item {{ request()->routeIs('patients*') ? 'active' : null }}">
                <a href="{{ route('patients.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Patients</p>
                </a>
            </li>
        @endif
        @if (Auth::user()->can('lab-view'))
            <li class="nav-item  {{ request()->routeIs('lab*') ? 'active' : null }}">
                <a href="{{ route('lab.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Labs</p>
                </a>
            </li>
        @endif
        @if (Auth::user()->can('designation-view'))
            <li class="nav-item  {{ request()->routeIs('hospital-designation*') ? 'active' : null }}">
                <a href="{{ route('hospital-designation.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Designations</p>
                </a>
            </li>
        @endif
        @if (Auth::user()->can('hospital-staff-view'))
            <li class="nav-item  {{ request()->routeIs('hospital-staff*') ? 'active' : null }}">
                <a href="{{ route('hospital-staff.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Staffs</p>
                </a>
            </li>
        @endif

        <li class="nav-item">
            <a href="#" class="nav-link
                        {{ request()->routeIs('user*') ? 'active' : '' }}">
                <i class="las la-user-friends"></i>
                <p>
                    Appointments
                    <i class="las la-angle-right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview" style="padding-left: 20px;">
                @if (Auth::user()->can('appointments-view'))
                    <li
                        class="nav-item
                                    {{ request()->routeIs('allAppointments') ? 'active' : '' }}">
                        <a href="{{ route('allAppointments') }}" class="nav-link">
                            <i class="las la-circle-notch"></i>
                            <p>View All Appointments</p>
                        </a>
                    </li>
                @endif
                @if(Auth::user()->hasRole('Hospital Staff'))
                <li
                    class="nav-item
                                {{ request()->routeIs('appointments.index') ? 'active' : '' }}">
                    <a href="{{ route('assignedAppointments') }}" class="nav-link">
                        <i class="las la-circle-notch"></i>
                        <p>Assigned to me</p>
                    </a>
                </li>
                @endif

            </ul>
        </li>

    </ul>
</li>
