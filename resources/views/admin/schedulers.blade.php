<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Comunidadsn') }}</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    @if (env('APP_ENV') == 'local')
        <link href="{{ asset('/css/custom.css') }}" rel="stylesheet">
    @else
        <link href="./public/css/custom.css" rel="stylesheet">
    @endif

</head>

<body class="bg-primary" style="width: 100%">
    @include('admin.nav')
    <div class="container bg-primary">
        <div class="row">
            <div class="col-12 justify-content-lg-center ">

                <div class="container mt-5 ">
                    <div class="row">
                        <h4 class="text-center">Agenda Semanal</h4>
                        <div class="mb-4">
                            <a href="{{ route('dashboard') }}"><button type="button"
                                class="btn btn-dark">Volver</button></a>
                        </div>
                    

                        <div class="col-lg-12">
                            <div class="row">
                                @foreach ($week as $key => $days)
                                <div class="mb-4 col-lg-4 bg-dark">
                                    <h4 class="text-light bg-warning">{{ $key }}</h4>

                                    @foreach ($days as $day)
                                        <div class="col-lg-12 text-light">
                                            <h5>{{ $day['date'] . ' -- ' . $day['user'] }}</h5>
                                        </div>
                                    @endforeach
                                    {{-- @dump($day) --}}
                                </div>
                            @endforeach
                            </div>
                            
                        </div>


                        <div class="mb-4 col-lg-12">
                            <h4 class="text-center">Puntaje</h4>
                            <div class="row">
                                <div class="col-lg-4">
                                    <h5>Usuario</h5>
                                </div>
                                <div class="col-lg-8">
                                    <h5>Streamers APoyados</h5>
                                </div>
                                
                                @if (count($all) > 0)
                                    @foreach ($all as $user)
                                        <div class="col-lg-4 bg-secondary text-light"> {{ $user['name'] }}</div>
                        
                                        <div class="col-lg-6 bg-secondary">
                                            <div class="row">
                                            @foreach ($user['supported'] as $supported)
                                                <div class="col">{{ $supported->name }}</div>
                                            @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                    @else
                                    {{-- <h5>Usuario</h5> --}}
                                @endif
                                
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script src="https://kit.fontawesome.com/252b802fc2.js" crossorigin="anonymous"></script>
</body>

</html>
