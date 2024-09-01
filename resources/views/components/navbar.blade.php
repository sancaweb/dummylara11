<nav class="main-header navbar navbar-expand-md navbar-dark">
    <div class="container-fluid">
        <a href="{{ route('root') }}" class="navbar-brand">
            <img src="{{ asset('logo.png') }}" alt="PT Tricentrum Fortuna" class="brand-image img-circle elevation-3"
                style="opacity: .8">
            <span class="brand-text font-weight-light">Tri Centrum Fortuna</span>
        </a>

        <button class="navbar-toggler order-1 " type="button" data-toggle="collapse" data-target="#navbarCollapse"
            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse order-3" id="navbarCollapse">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="{{ route('root') }}" class="nav-link">
                        <i class="fas fa-tachometer-alt"></i>&nbsp;Dashboard</a>
                </li>
                @auth

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-microscope"></i>&nbsp;Next Menu</a>
                </li>

                @role('super admin')
                <li class="nav-item dropdown">
                    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false" class="nav-link dropdown-toggle">
                        <i class="nav-icon fas fa-user-shield"></i>&nbsp;Users</a>
                    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">

                        <li>
                            <a href="{{ route('user') }}" class="dropdown-item">

                                Data Users
                            </a>
                        </li>

                        <li class="dropdown-divider"></li>

                        <!-- Level two dropdown-->
                        <li class="dropdown-submenu dropdown-hover">
                            <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Roles &
                                Permissions</a>
                            <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                                <li>
                                    <a tabindex="-1" href="{{ route('rolepermission') }}" class="dropdown-item">Data</a>
                                </li>
                                <li><a href="{{ route('assignPermission.assign') }}" class="dropdown-item"> Assign Permissions</a></li>
                            </ul>
                        </li>
                        <!-- End Level two -->





                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a id="dropDownTools" href="#" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false" class="nav-link dropdown-toggle">
                        <i class="nav-icon fas fa-tools"></i>&nbsp;Tools</a>
                    <ul aria-labelledby="dropDownTools" class="dropdown-menu border-0 shadow">

                        <li>
                            <a href="{{ route('activity') }}" class="nav-link">
                                <i class="fas fa-history"></i>&nbsp;Logs</a>
                        </li>

                        <li class="dropdown-divider"></li>





                    </ul>
                </li>


                @endrole
                <li class="nav-item ">
                    <a href="#" class="nav-link" data-toggle="modal"
                    data-target="#logoutModal">
                        <i class="nav-icon fas fa-sign-out-alt"></i> &nbsp; Logout
                    </a>
                </li>
                @endauth
                @guest
                <li class="nav-item ">
                    <a class="nav-link"href="{{ route('login') }}">
                        <i class="nav-icon fas fa-sign-in-alt"></i> &nbsp; Login
                    </a>
                </li>
                @endguest
            </ul>


        </div> <!-- ./end left navbar -->


    </div>
</nav>
