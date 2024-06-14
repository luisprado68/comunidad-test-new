<div>
    <nav class="p-0 mb-5 navbar-dark {{$background}}">
{{--       @dump(session('user'))--}}
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

            <div class="pr-2 mt-4 col-lg-4 col col-md-4 col-sm-10">
                <div class="dropdown">
                    <div class="row">
                        <div class="col-7" style="margin-left: 8%">
                            <input class=" form-control"  type="text" placeholder="Buscar Streamer" aria-label="Search" wire:model="search">
                            @if ($show_result)
                                <ul class="col-7 dropdown-menu show" data-bs-popper="static" style="margin-left: 8%">
                                    @if (count($list))

                                        @foreach($list as $user_found)
                                        <li><a href="{{route('summary-user',['id' => $user_found->id ])}}" class="dropdown-item item-list">{{ $user_found->channel }}</a></li>
                                        @endforeach
                                    @endif
                                </ul>
                            @endif
                            @if (isset($search) && count($list) == 0)
                                <ul class="col-7 dropdown-menu show" data-bs-popper="static" style="margin-left: 8%">
                                    <li><a href="#" class="dropdown-item not-found">No se encontro streamer</a></li>
                                </ul>
                            @endif

                        </div>


                        <div class="col-1">
                            <button class="btn btn-secondary dropdown-toggle" wire:click='setSearch' data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-search"></i>
                                <div wire:loading wire:target="setSearch">

                                    <div class="spinner-border text-light" role="status" style="width: 15px;height:15px">
                                        <span class="visually-hidden"></span>
                                      </div>

                                </div>
                            </button>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-12"></div>
            <div class="col-lg-4 col col-md-12 col-sm-12">
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


    </nav>

</div>
