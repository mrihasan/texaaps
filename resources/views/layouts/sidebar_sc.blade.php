<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
        {{--<img src="{!! asset('images/eisLogoTaefc05.png')!!}" alt="Logo"--}}
        {{--class="brand-image img-circle elevation-3"--}}
        {{--style="opacity: .8">--}}
        <span class="brand-text font-weight-light">{{ config('app.name', 'EIS') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-compact" data-widget="treeview"
                role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-language " style="color: yellow"></i>
                        <p style="color: yellow">
                            {{ Config::get('languages')[App::getLocale()] }}
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @foreach (Config::get('languages') as $lang => $language)
                            @if ($lang != App::getLocale())
                                <li class="nav-item">
                                    <a href="{{ route('lang.switch', $lang) }}" class="nav-link">
                                        <i class="fa fa-language nav-icon"></i>
                                        <p>{{$language}}</p>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </li>

                <li class="nav-item @yield('dashboard_mo')">
                    <a href="{{ route('home') }}" class="nav-link @yield('dashboard')">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            {{ __('all_settings.Dashboard') }}
                        </p>
                    </a>
                </li>
                <li class="nav-item @yield('myprofile')">
                    <a href="{{ route('myprofile') }}" class="nav-link @yield('myprofile')">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            My Profile
                        </p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
