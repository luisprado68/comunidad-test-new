@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">

            <div class="pt-5 col-md-12 w-100">
                @if (session()->has('user') && session('status') == 0)

                    @include('link')
                @else
                    <div class="pb-3 text-center col">
                        <h4 class="text-center text-light">BIENVENIDO A LA COMUNIDAD MAS GRANDE DE STREAMERS</h4>
                        <h4 class="text-center text-light">DONDE PODRÁS CONOCER CREADORES DE CONTENIDO</h4>
                        <h4 class="text-center text-light">PARA QUE PUEDAN INTERACTUAR,HACER COLABORACIONES, TORNEOS Y EVENTOS.</h4>
                    </div>
                    <div class="mt-4 card bg-dark">
                        {{-- <div class="card-body fondo_claro"> --}}
                        <div class="card-body bg-dark">
                            <div class="row">

                                <div class="col-12">
                                    <div class="col-md-12 w-100">
                                        <div class="card ">
                                            {{-- <div class="card-header">{{ __('Dashboard') }}</div> --}}

                                            <div class="row">
                                                <div class="m-2 col-12">

                                                    <h1 class="pb-3 text-center">QUE TENGO QUE HACER?</h1>


                                                    <ul style="list-style: none;" class="p-0 text-center">
                                                        <li class="pb-2">
                                                            <h5 class="text-center">Apoya los directos de los streamers y genera puntos.</h5>
                                                        </li>
                                                        <li>
                                                            <h5 class="text-center">Esos puntos te servirán para que tengas apoyo y muchos
                                                                beneficios.</h5>
                                                            {{-- {{session('test')}} --}}
                                                        </li>
                                                    </ul>
                                                </div>
                                                @if (!session()->has('user'))
                                                    <div class="col-md-6">
                                                        <div class="text-center card-body">
                                                            @if (env('APP_ENV') == 'local')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('login-test') }}"><button type="button"
                                                                        class="btn btn-lg twich-button" style=""><i
                                                                            class="fa-brands fa-twitch"></i> Únete
                                                                        con
                                                                        TWITCH</button></a>
                                                            @else
                                                                <a class="dropdown-item" href="{{ route('login_twich') }}"><button
                                                                        type="button" class="btn btn-lg twich-button"
                                                                        style=""><i class="fa-brands fa-twitch"></i>
                                                                        Únete
                                                                        con
                                                                        TWITCH</button></a>
                                                            @endif

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="text-center card-body">
                                                            <button type="button" class="btn btn-lg kick-button"
                                                                style="">
                                                                <i class="fa-brands fa-kickstarter"></i> Únete con
                                                                KICK</button>
                                                            
                                                        </div>
                                                    </div>
                                                @endif


                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-s-12">
                                    <div class="pt-5 col-md-12 s-12 w-100">
                                        <div class="card banner">
                                            <h6 class="text-center">Streamers en directo.</h6>

                                            <div class="row">
                                                <div class="p-3 col-md-6">
                                                    <div class="card streamer-uno" style="height: 150px; background-color: violet">
                                                        {{-- <div class="w-50"></div> --}}
                                                    </div>
                                                </div>
                                                <div class="p-3 col-md-6">
                                                    <div class="card streamer-dos" style="height: 150px;background-color: violet">
                                                        {{-- <div class="w-50"></div> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-s-12">
                                    <div class="pt-5 col-md-12 w-100">
                                        <div class="card banner_kick">
                                            <h6 class="text-center">Streamers en directo.</h6>

                                            <div class="row">
                                                <div class="p-3 col-md-6">
                                                    <div class="card under" style="height: 150px">
                                                        <div class="w-50"></div>
                                                    </div>
                                                </div>
                                                <div class="p-3 col-md-6">
                                                    <div class="card under" style="height: 150px">
                                                        <div class="w-50"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    {{-- @if (isset($times))
                        <input type="text" class="form-control" aria-label="Default" name="times" id="times"
                        aria-describedby="inputGroup-sizing-default" value="{{ $times }}" style="display: none">
                    @endif --}}
                   
                @endif



            </div>

        </div>
    </div>
    @include('layouts.footer')
@endsection
@push('chatters')
    {{-- @if (env('APP_ENV') == 'local')
        <script src="{{ asset('/js/setTime.js') }}"></script>
    @else
        <script src="./public/js/setTime.js"></script>
    @endif --}}
@endpush
