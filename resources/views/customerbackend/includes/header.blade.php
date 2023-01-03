<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    @php
        $user = Auth::user()->id;
        $useremail = Auth::user()->email;
        $dealers = DB::table('dealer_users')->where('email', $useremail)->get();
        $dealer_ids = [];
        foreach($dealers as $dealer){
            $dealer_ids[] = $dealer->id;
        }

        $usercompanies = \App\Models\DealerUserCompany::whereIn('dealer_user_id', $dealer_ids)->get();
        // dd($usercompanies);
        $currentcomp = \App\Models\DealerUserCompany::where('dealer_user_id', $user)
            ->where('is_selected', 1)
            ->first();
    @endphp
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link tog-btn" data-widget="pushmenu" href="#" role="button"><i class="las la-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto right-icons">

        <li class="nav-item dropdown c-name">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false" v-pre>
                <i class="las la-building m-only-icon"></i>
                <span class="c-names">
                    {{ $currentcomp->company->name }}{{ $currentcomp->branch->is_headoffice == 1 ? ' (Head Office)' : ' (' . $currentcomp->branch->name . ')' }}
                </span>
            </a>

            <div class="dropdown-menu dropdown-menu-right company-dropdown" aria-labelledby="navbarDropdown">
                @foreach ($usercompanies as $usercompany)
                    <a class="dropdown-item" href="{{ route('switch.selectedcustomer', $usercompany->id) }}">
                        <i class="las la-building"></i> {{ $usercompany->company->name }}{{ $usercompany->branch->is_headoffice == 0 ? ' (' . $usercompany->branch->name . ')' : ' (Head Office)' }}
                    </a>
                @endforeach

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>

        <li class="nav-item dropdown c-name c-name-login">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false" v-pre>
                <i class="las la-user m-only-icon"></i>
                <span class="c-names">{{ Auth::user()->name }}</span>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                {{-- <a class="dropdown-item" href="{{ route('user.edit', Auth::user()->id) }}">
                    {{ __('Profile') }}
                </a> --}}

                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
        <div class="toggle-btn">
            <div class="toggle-wrap">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="toggle-wrap">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="toggle-wrap">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </ul>
</nav>
