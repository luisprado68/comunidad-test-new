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

                        <form action="{{ route('admin-post') }}" method="POST">
                            @csrf
                            <div class="d-flex justify-content-center">
                                <div class="card w-50 bg-secondary">

                                    <div class="p-0 m-0 card-body bg-secondary">
                                        <div class="bg-black card ">
                                            <h5 class="mt-2 text-center card-title text-light ">Edit User</h5>
                                        </div>

                                        <div class="row justify-content-center">

                                            <div class="mt-3 mb-3 col-8">

                                                <input type="text" class="form-control" aria-label="Default" name="id"
                                                    aria-describedby="inputGroup-sizing-default" value="{{ $user->id }}" style="display: none">
                                            </div>
                                            <div class="mt-3 mb-3 col-8">
                                                <label class="block mb-2 text-sm font-medium text-white" for="name">Name</label>
                                                <input type="text" class="form-control" aria-label="Default" name="name"
                                                    aria-describedby="inputGroup-sizing-default" value="{{ $user->name }}">
                                            </div>
                                            <div class="mt-3 mb-3 col-8">
                                                <label class="block mb-2 text-sm font-medium text-white"
                                                    for="email">Email</label>
                                                <input type="text" class="form-control" aria-label="Default" name="email"
                                                    aria-describedby="inputGroup-sizing-default" value="{{ $user->email }}">
                                            </div>
                                            <div class="mt-3 mb-3 col-8">
                                                <label class="block mb-2 text-sm font-medium text-white"
                                                for="range">Rango</label>
                                                <select class="mb-3 form-select" aria-label=".form-select example" name="range" id="range">
                                                    {{-- <option value="{{''}} selected>Seleccione una opción</option> --}}
                                                    @foreach ($ranges as $range)
                                                    <option value="{{ $range->name }}" {{ ( $range->id == $user->roles->last()->id) ? 'selected' : '' }}> {{ $range->name }} </option>
                                                    @endforeach
                                                  </select>
                                            </div>
                                            <div class="mb-3 col-8 ">
                                                <label class="block mb-2 text-sm font-medium text-white"
                                                for="range">Rol</label>
                                                <select class="mb-3 form-select" aria-label=".form-select example" name="role" id="role">
                                                    {{-- <option value="{{''}} selected>Seleccione una opción</option> --}}
                                                    @foreach ($roles as $role)

                                                    <option value="{{ $role->name }}" {{ in_array($role->name, $userRoles) ? 'selected' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                    @endforeach
                                                  </select>
                                            </div>
                                            <div class="mb-3 col-8 ">
                                                <label class="block mb-2 text-sm font-medium text-white"
                                                for="range">Equipo</label>
                                                <select class="mb-3 form-select" aria-label=".form-select example" name="team" id="team">
                                                    {{-- <option value="{{''}} selected>Seleccione una opción</option> --}}
                                                    @foreach ($teams as $team)

                                                    <option value="{{ $team->id }}" {{ ( $team->id == $user->current_team_id) ? 'selected' : '' }}> {{ $team->name }} </option>
                                                    @endforeach
                                                  </select>
                                            </div>
                                            <div class="mt-3 mb-3 col-8">
                                                <input class="form-check-input" type="checkbox"  type="checkbox" name="status"  value="1" @if (isset($user)) @if ($user->status==1) checked @endif @endif id="">
                                                <label class="form-check-label" for="flexCheckChecked">
                                                    Active
                                                </label>
                                            </div>

                                            <div class="mt-2 mb-4 col-6" style="display: block;margin-right:105px">
                                                <button type="submit" class="btn btn-success">Guardar</button>
                                                @if(!isset($user->current_team_id))
                                                    <a href="{{ route('actions-teams')  }}"><button type="button" class="btn btn-danger">Cancel</button></a>
                                                @else
                                                    <a href="{{ route('team-show', $user->current_team_id) }}"><button type="button" class="btn btn-danger">Cancel</button></a>
                                                @endif
                                                @if(auth()->user()->can('users-pass'))
                                                    <a href="{{ route('user-update-pass', $user->id) }}"><button type="button" class="btn btn-primary">Actualizar Pass</button></a>
                                                @endif


                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </form>

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
