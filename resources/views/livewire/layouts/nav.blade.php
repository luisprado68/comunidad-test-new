<div>
    <nav class="p-0 mb-5 navbar navbar-expand-lg navbar-dark banner-dark">
     
        <div class="row">
            <div class="col-4">
                @if (session()->has('user'))
                <a  href="{{ route('home') }}"><img src="{{asset('/img/logo_co.png')}}" alt="tag"
                    class="logo rounded-circle"></a>
                
                @else
                    <a  href="{{ route('home') }}"><img src="{{asset('/img/logo_co.png')}}" alt="tag"
                        class="logo rounded-circle"></a>
                @endif
            </div>
            <div class="mt-4 col-8">
                <div class="dropdown">
                    <div class="row">
                        <div class="ml-3 col">
                            <input class="form-control mr-sm-2 "  type="search" placeholder="Buscar Streamer" aria-label="Search" wire:model="search">
                        </div>
                       
                 
                        <div class="col-3">
                            <button class="btn btn-secondary dropdown-toggle" wire:click='setSearch' data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-search"></i>
                            </button>
                            @if ($show_result)
                                <ul class="dropdown-menu show" data-bs-popper="static">
                                    @if (count($list))
                                        
                                        @foreach($list as $user_found)
                                        <li><a href="{{route('summary-user',['id' => $user_found->id ])}}" class="dropdown-item">{{ $user_found->channel }}</a></li>
                                        @endforeach
                                    @endif
                                    
                                </ul>
                            @endif
                           
                        </div>
                    </div>
                    
            
            
          </div>
            </div>
        </div>
        
        
            {{-- @if (env('APP_ENV') == 'local')
                <img src="{{asset('/img/banner.png')}}" alt="tag"
                class="mr-4 banner-fondo">
            @else
            <img src="{{'./public/img/banner.png'}}" alt="tag"
            class="mr-4 banner-fondo">
            @endif --}}
        
        {{-- @dd(session('user')['profile_image_url']) --}}
        {{-- <nav class="p-0 m-0 navbar navbar-expand-lg navbar-dark bg-dark"> --}}
        {{-- <div class="container-fluid"> --}}
        <div class="container-fluid">
            {{-- <div class="row"> --}}
            {{-- <div class="col-1">
                    <a class="navbar-brand" href="{{ route('home') }}"><img src="{{ asset('/img/logo_co.avif') }}"
                            alt="tag" width="100"height="100"></a>
                </div> --}}
            {{-- <div class="col-4 offset-3 ">
                    <a class="navbar-brand" href="#"><img src="{{ asset('/img/flayer.jpg') }}" alt="tag"
                            width="500"height="100"></a>
                </div> --}}
            {{-- <div class="col-lg-1 offset-lg-11 col-sm-1 offset-sm-6 "> --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
    
            <div class=" collapse navbar-collapse" id="navbarNavDropdown">
                {{-- ms-auto --}}
                <ul class="navbar-nav ms-auto">
                    {{-- <li class="nav-item">
                                <a class="mx-2 nav-link active item-nav" aria-current="page"
                                    href="{{ route('home') }}">Inicio</a>
                            </li> --}}
                    <li class="nav-item dropdown">
                        <a class="mx-2 nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{-- @if (env('APP_ENV') == 'local') --}}
                            {{-- <img src="{{ asset('/img/logo.webp') }}" alt="tag" class="profile-img"> --}}
                            @if (session()->has('user'))
                                {{-- <img src="{{ asset('/img/logo.webp') }}" alt="tag" class="profile-img"> --}}
                                <img src="{{ session('user')['profile_image_url'] }}" alt="tag"
                                    class="profile-img rounded-circle">
                            @else
                                {{-- @if (env('APP_ENV') == 'local') --}}
                                    <img src="{{ asset('/img/logo.webp') }}" alt="tag" class="profile-img">
                                {{-- @else
                                    <img src="./public/img/logo.webp" alt="tag" class="profile-img">
                                @endif --}}
    
                            @endif
    
                        </a>
                        <ul class="m-0 dropdown-menu overlay" aria-labelledby="navbarDropdownMenuLink">
    
                            @if (session()->has('user'))
                                {{-- <input type="text" class="form-control" aria-label="Default" name="name"
                                aria-describedby="inputGroup-sizing-default"
                                value="{{ $user->name }}"> --}}
                                <li><a class="dropdown-item" href="{{ route('profile') }}">Perfil</a></li>
                                <li><a class="dropdown-item" href="{{ route('summary') }}">Resumen</a></li>
                                <li><a class="dropdown-item" href="{{ route('support') }}">Apoyar</a></li>
                                <li><a class="dropdown-item" href="{{ route('schedule') }}">Agendar</a></li>
                                <li><a class="dropdown-item" href="{{ route('my_agendas') }}">Mis Agendas</a></li>
                                <li><a class="dropdown-item" href="{{ route('history') }}">Historial</a></li>
                                <li><a class="dropdown-item" href="{{ route('donation') }}">Donaciones</a></li>
                                <li><a class="dropdown-item" href="{{ route('logout_twich') }}">Cerrar Sesión</a></li>
                            @else
                                <li><a class="dropdown-item" href="{{ route('home') }}">Inicio</a></li>
                            @endif
    
                        </ul>
                    </li>
    
                </ul>
            </div>
        </div>
    </nav>
   
</div>
