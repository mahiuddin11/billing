<style>
    .nav_color {
        background-color: #0061f2 !important;
        background-image: linear-gradient(135deg, #2600bd 0%, rgb(132 101 160 / 80%) 100%) !important;
    }

    .color {
        color: #fff !important;
    }
</style>

<nav
    class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl nav_color navbar-dark">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="ficon"
                            data-feather="menu"></i></a></li>
            </ul>
        </div>
        <li class="nav-item d-none d-lg-block text-left"><a href="{{ route('runSchedul') }}"
                class="btn btn-info">Sync</a></li>

        <ul class="nav navbar-nav align-items-center ml-auto">

            {{-- <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon"
                        data-feather="moon"></i></a></li> --}}

            <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link"
                    id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <div class="color user-nav d-sm-flex d-none"><span
                            class="user-name font-weight-bolder">{{ auth()->user()->customer->company_name ?? '' }}</span><span
                            class="user-status">

                            @if (auth()->user()->is_admin == 1)
                                Admin
                            @elseif(auth()->user()->is_admin == 2)
                                Customer
                            @elseif(auth()->user()->is_admin == 3)
                                Mac Admin
                            @elseif(auth()->user()->is_admin == 4)
                                Employe
                            @elseif(auth()->user()->is_admin == 5)
                                Employe
                            @endif
                @if(auth()->user()->employee)
                 </span></div><span class="avatar"><img class="round" src="{{ asset("storage/photo/".auth()->user()->employee->image) }}"
                     alt="avatar" height="40" width="40"><span
                     class="avatar-status-online"></span></span>
                 @else
                 </span></div><span class="avatar"><img class="round" src="{{ asset('dummy-image.jpg') }}"
                     alt="avatar" height="40" width="40"><span
                     class="avatar-status-online"></span></span>
                 @endif
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user"><a class="dropdown-item"
                        href="{{ route('change.password') }}"><i class="mr-50" data-feather="edit"></i>
                        Update Profile</a>
                    <div class="dropdown-divider"></div>

                    <a href="{{ url('/logout') }}" class="dropdown-item"><i class="mr-50" data-feather="power"></i>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>
