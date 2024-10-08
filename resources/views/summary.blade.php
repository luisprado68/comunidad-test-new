@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            @if (session()->has('user') && session('status') == 0 || !isset($user->team))
                @include('link')
            @else
                @if (isset($user_watched))
                    @include('status-user', ['user' => $user])
                @endif
                @if(session('message'))
                        <div class="toast-container position-fixed top-0 end-0 p-3">
                            <div id="liveToast" class="toast text-bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="toast-header">
{{--                                    <img src="..." class="rounded me-2" alt="...">--}}
                                    <strong class="me-auto">Notificación</strong>
{{--                                    <small>11 mins ago</small>--}}
                                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                                <div class="toast-body">
                                    {{session('message')}}
                                </div>
                            </div>
                        </div>

                @endif
                @include('status', ['user' => $user])
                <div class="pt-1 mb-4 col-md-12 w-100">
                    <div class="card bg-dark">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="card banner-border">
                                        <div class="card-body banner">
                                            <h3 class="text-center text-light">Rango</h3>
                                        </div>

                                    </div>
                                    <div class="text-center col justify-content-center">
                                        {{-- @if (env('APP_ENV') == 'local') --}}
                                            @if ($user->range->id == 1 )
                                                <img src="{{ asset('/img/BRONCE.png') }}" alt="tag" class="m-2 w-50">
                                            @elseif($user->range->id == 2 )
                                                <img src="{{ asset('/img/PLATA.png') }}" alt="tag" class="m-2 w-50">
                                            @elseif($user->range->id == 3 )
                                                <img src="{{ asset('/img/ORO.png') }}" alt="tag" class="m-2 w-50">
                                            @elseif($user->range->id == 4 )
                                                <img src="{{ asset('/img/PLATINO.png') }}" alt="tag" class="m-2 w-50">
                                            @elseif($user->range->id == 5 )
                                                <img src="{{ asset('/img/ESMERALDA.png') }}" alt="tag" class="m-2 w-50">
                                            @elseif($user->range->id == 6 )
                                                <img src="{{ asset('/img/DIAMANTE.png') }}" alt="tag" class="m-2 w-50">
                                            @elseif($user->range->id == 7 )
                                                <img src="{{ asset('/img/HIERRO.png') }}" alt="tag" class="m-2 w-50">
                                            @elseif($user->range->id == 8 )
                                                <img src="{{ asset('/img/LIDERES.png') }}" alt="tag" class="m-2 w-50">
                                            @endif

                                        {{-- @else
                                            @if ($user->range->id == 1 )
                                                <img src="./public/img/bronce.svg" alt="tag" class="m-2 w-50">
                                            @elseif($user->range->id == 2 )
                                                <img src="./public/img/plata.svg" alt="tag" class="m-2 w-50">
                                            @elseif($user->range->id == 3 )
                                                <img src="./public/img/oro.svg" alt="tag" class="m-2 w-50">
                                            @elseif($user->range->id == 4 )
                                                <img src="./public/img/platino.svg" alt="tag" class="m-2 w-50">
                                            @endif
                                        @endif --}}
                                        {{-- <h4 class="text-center text-light">{{$user->range->name}}</h4> --}}
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="mb-3 card banner-border">
                                        <div class="card-body banner">
                                            <h3 class="text-center text-light">Referidos</h3>
                                        </div>
                                    </div>
                                    <div class="text-center col justify-content-center">
                                        @if (count($ref)> 0)
                                            @foreach ( $ref as $item)
                                                <h3 class="mb-1 text-center text-light">{{$item}}</h3>
                                            @endforeach
                                        @endif


                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                @if (!isset($user_watched))
                    @include('share')
                @endif

            @endif


        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toastLiveExample = document.getElementById('liveToast');
        const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample);
        toastBootstrap.show();
    </script>
@endsection

