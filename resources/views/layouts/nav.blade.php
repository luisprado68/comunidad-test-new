<nav class=" mb-5 navbar-dark {{$class_nav}}">
        <div class="px-3">

            <div class="row">
                <div class="col-lg-2 col-md-auto col-sm-12">
                    @if (session()->has('user'))
                        <a  href="{{ route('home') }}"><img src="{{asset('/img/logo_co.png')}}" alt="tag"
                                                            class="logo rounded-circle"></a>

                    @else
                        <a  href="{{ route('home') }}"><img src="{{asset('/img/logo_co.png')}}" alt="tag"
                                                            class="logo rounded-circle"></a>
                    @endif
                </div>

                <div class="col-lg-2 col-md-4 col-sm-12"></div>
                <div class="col-lg-8 col col-md-12 col-sm-12">
                    <div class="d-flex justify-content-end icon-img">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown">
                                <a class="mx-2 nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    @if (session()->has('user'))
                                        <img src="{{ session('user')['profile_image_url'] }}" alt="tag"
                                             class="profile-img rounded-circle">
                                    @else
                                        <img src="{{ asset('/img/logo.webp') }}" alt="tag" class="profile-img">
                                    @endif

                                </a>
                                <ul class="m-0 dropdown-menu menu-nav overlay" aria-labelledby="navbarDropdownMenuLink">

                                    @if (session()->has('user'))
                                        <li><a class="dropdown-item item-list" href="{{ route('profile') }}">Perfil</a></li>
                                        <li><a class="dropdown-item item-list" href="{{ route('summary') }}">Resumen</a></li>
                                        <li><a class="dropdown-item item-list" href="{{ route('support') }}">Apoyar</a></li>
                                        <li><a class="dropdown-item item-list" href="{{ route('schedule') }}">Agendar</a></li>
                                        <li><a class="dropdown-item item-list" href="{{ route('my_agendas') }}">Mis Agendas</a></li>
                                        <li><a class="dropdown-item item-list" href="{{ route('history') }}">Historial</a></li>
                                        <li><a class="dropdown-item item-list" href="{{ route('donation') }}">Donaciones</a></li>
                                        <li><a class="dropdown-item item-list" href="{{ route('logout_twich') }}">Cerrar Sesión</a></li>
                                    @else
                                        <li><a class="dropdown-item item-list" href="{{ route('home') }}">Inicio</a></li>
                                    @endif

                                </ul>
                            </li>

                        </ul>
                    </div>


                </div>
            </div>
        </div>



    </nav>
