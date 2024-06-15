@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            @if (session()->has('user') && session('status') == 0 || !isset($user->team))
                @include('link')
            @else
                @include('status', ['user' => $user])
                <div class="pt-1 col-md-12 w-100">
                    <div class="card bg-secondary">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="card banner">
                                        <div class="card-body banner">
                                            <h3 class="text-center text-light">Últimos Apoyos</h3>
                                        </div>

                                    </div>
                                    @foreach ($scores as $score)
                                    <div class="mt-2 mb-2 card bg-dark text-light">
                                        <div class="row">
                                                <div class="col-lg-12">
                                                    {{-- <div class="text-lg font-bold text-center">mar. 03 oct. 04:05</div> --}}
                                                    <div class="text-lg font-bold text-center">{{$score['date']}}</div>
                                                </div>
                                                <div class="col">
                                                    <div class="pt-3 text-lg font-bold">{{$score['channel']}}</div>
                                                </div>
                                                @if ($score['time'] >= 50 && $score['time'] <= 59)
                                                <div class="col">
                                                    <div class="m-1 text-center bg-success text-light">Apoyo Completo</div>
                                                </div>
                                                @else
                                                <div class="col">
                                                    <div class="m-1 text-center bg-danger text-light">Poco tiempo en el stream
                                                    </div>
                                                </div>
                                                @endif

                                        </div>
                                    </div>
                                    @endforeach

                                    {{-- <div class="mt-2 card bg-dark text-light">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="text-lg font-bold text-center">mar. 03 oct. 04:05</div>
                                            </div>
                                            <div class="col">
                                                <div class="pt-3 text-lg font-bold">LobatoXY</div>
                                            </div>
                                            <div class="col">
                                                <div class="m-1 text-center bg-success text-light">Apoyo Completo</div>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="card banner ">
                                        <div class="card-body banner">
                                            <h3 class="text-center text-light">NeoCoins</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center col-4">
                                    {{-- @if (env('APP_ENV') == 'local')
                                    <img src="{{ asset('/img/rango.jpg') }}" alt="tag" class="m-5 w-25">
                                @else
                                    <img src="./public/img/rango.jpg" alt="tag" class="m-5 w-25">
                                @endif --}}

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                {{-- <input type="text" class="form-control" aria-label="Default" name="times" id="times"
                    aria-describedby="inputGroup-sizing-default" value="{{ $times }}" style="display: none"> --}}
            @endif


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
