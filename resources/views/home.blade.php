@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">

            <div class="pt-5 col-md-12 w-100 section-home">
                @if (session()->has('user') && session('status') == 0)

                    @include('link')
                @else
                    <div class="pb-3 text-center col">
                        <h4 class="text-center text-light">BIENVENIDO A LA COMUNIDAD MAS GRANDE DE STREAMERS</h4>
                        <h4 class="text-center text-light">DONDE PODRÁS CONOCER CREADORES DE CONTENIDO</h4>
                        <h4 class="text-center text-light">PARA QUE PUEDAN INTERACTUAR,HACER COLABORACIONES, TORNEOS Y EVENTOS.</h4>
                    </div>
                    <div class="mt-4 card bg-dark">
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
                                                    <div class="col-lg-4 col-md-4">
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
                                                    <div class="col-lg-4 col-md-4">
                                                        <div class="text-center card-body">
                                                            @if (env('APP_ENV') == 'local')
                                                                <a class="dropdown-item" href="{{ route('login_trovo') }}"><button type="button" class="btn btn-lg trovo-button"
                                                                                                                                                   style="">
                                                                        <img src="{{ asset('/img/trovo.png') }}" alt="" width="25px">
                                                                        Únete con
                                                                        TROVO</button></a>
                                                            @else
                                                                <a class="dropdown-item" href="https://trovo.live/" target="_blank"><button type="button" class="btn btn-lg trovo-button"
                                                                                                                                            style="">
                                                                        <img src="{{ asset('/img/trovo.png') }}" alt="" width="25px">
                                                                        Únete con
                                                                        TROVO</button></a>
                                                            @endif


                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4">
                                                        <div class="text-center card-body">
                                                            <a class="dropdown-item" href="https://kick.com/" target="_blank"><button type="button" class="btn btn-lg kick-button"
                                                                style="">
                                                                <i class="fa-brands fa-kickstarter"></i> Únete con
                                                                KICK</button></a>

                                                        </div>
                                                    </div>
                                                @endif


                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-s-12">
                                    <div class="pt-5 col-md-12 s-12 w-100">
                                        <div class="card banner">
                                            <h6 class="text-center">Streamers en directo.</h6>

                                            <div class="row">
                                                <div class="p-3 col-md-12">
                                                    <div class="card streamer-uno" style="height: 150px; background-color: violet">
                                                        {{-- <div class="w-50"></div> --}}
                                                    </div>
                                                </div>
                                                <div class="p-3 col-md-12">
                                                    <div class="card streamer-dos" style="height: 150px;background-color: violet">
                                                        {{-- <div class="w-50"></div> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-s-12">
                                    <div class="pt-5 col-md-12 w-100">
                                        <div class="card banner_trovo">
                                            <h6 class="text-center">Streamers en directo.</h6>

                                            <div class="row">
                                                <div class="p-3 col-md-12">
                                                    <div class="card under" style="height: 150px">
                                                        <div class="w-50"></div>
                                                    </div>
                                                </div>
                                                <div class="p-3 col-md-12">
                                                    <div class="card under" style="height: 150px">
                                                        <div class="w-50"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-s-12">
                                    <div class="pt-5 col-md-12 s-12 w-100">
                                        <div class="card banner_kick">
                                            <h6 class="text-center">Streamers en directo.</h6>

                                            <div class="row">
                                                <div class="p-3 col-md-12">
                                                    <div class="card under" style="height: 150px">
                                                        <div class="w-50"></div>
                                                    </div>
                                                </div>
                                                <div class="p-3 col-md-12">
                                                    <div class="card under" style="height: 150px">
                                                        <div class="w-50"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 ">
                                    <div class="px-3 pt-3 col">
                                        <div class="row">

                                            <div class="text-center col-12 text-light card " style="background-color: #6474b8">
                                                <h4>Sumate a la comunidad en Discord!</h4>
                                                <a href="https://discord.gg/uncB86TTSg" target="_blank"><i class='pb-2 fab fa-discord animate__animated animate__bounce' style='font-size:30px;color:white'></i></a>
                                                {{-- <h1 class="animate__animated animate__bounce">An animated element</h1> --}}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="pt-3 col-12">

                                    <div class="col-md-12 w-100">
                                        <div class="card" style="background-color: #031633">
                                            {{-- <div class="card-header">{{ __('Dashboard') }}</div> --}}

                                            <div class="row">
                                                <div class="col-12">
                                                    <h1 class="pb-3 text-center text-light">Top de la semana</h1>
                                                    <div class="row">
{{--                                                        @dump($twoElements1)--}}
                                                        @if (count($twoElements1))
                                                            @foreach ($twoElements1 as $twoElements)
                                                            <div class="mb-4 text-center col-lg-4 col-md-4 col-sm-6">
                                                                    <div class=" text-light">
                                                                        <p style="display: inline"><b>{{'#' .$top++}}</b></p>
                                                                        <a class="text-light link_streamer" style="text-decoration: none" href="{{ route('summary-user',['id' => $twoElements['user_id'] ]) }}"><p>{{$twoElements['channel']}}</p>
                                                                            <img src="{{$twoElements['img_profile']}}" alt="tag"
                                                                                 class="rounded-circle" width="100px"></a>
                                                                    </div>
                                                                </div>

                                                            @endforeach
                                                        @endif

                                                        @if (count($twoElements2))
                                                            @foreach ($twoElements2 as $twoElements)
                                                            <div class="mb-4 text-center col-lg-4 col-md-4 col-sm-6">

                                                                <div class=" text-light">
                                                                    <p style="display: inline"><b>{{'#' .$top++}}</b></p>
                                                                    <a class="text-light link_streamer" style="text-decoration: none" href="{{ route('summary-user',['id' => $twoElements['user_id'] ]) }}"><p>{{$twoElements['channel']}}</p>
                                                                        <img src="{{$twoElements['img_profile']}}" alt="tag"
                                                                             class="rounded-circle" width="100px"></a>
                                                                </div>
                                                                </div>

                                                            @endforeach
                                                        @endif

                                                        @if (count($twoElements3))
                                                            @foreach ($twoElements3 as $twoElements)
                                                            <div class="mb-4 text-center col-lg-4 col-md-4 col-sm-6">

                                                                <div class=" text-light">
                                                                    <p style="display: inline"><b>{{'#' .$top++}}</b></p>
                                                                    <a class="text-light link_streamer" style="text-decoration: none" href="{{ route('summary-user',['id' => $twoElements['user_id'] ]) }}"><p>{{$twoElements['channel']}}</p>
                                                                        <img src="{{$twoElements['img_profile']}}" alt="tag"
                                                                             class="rounded-circle" width="100px"></a>
                                                                </div>
                                                                </div>

                                                            @endforeach
                                                        @endif

                                                            @if (count($twoElements4))
                                                                @foreach ($twoElements4 as $twoElements)
                                                                <div class="mb-4 text-center col-lg-4 col-md-4 col-sm-6">

                                                                    <div class=" text-light">
                                                                        <p style="display: inline"><b>{{'#' .$top++}}</b></p>
                                                                        <a class="text-light link_streamer" style="text-decoration: none" href="{{ route('summary-user',['id' => $twoElements['user_id'] ]) }}"><p>{{$twoElements['channel']}}</p>
                                                                            <img src="{{$twoElements['img_profile']}}" alt="tag"
                                                                                 class="rounded-circle" width="100px"></a>
                                                                    </div>
                                                                    </div>

                                                                @endforeach
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>

                        </div>

                    </div>


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
