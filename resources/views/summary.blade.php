@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            @if (session()->has('user') && session('status') == 0)
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
                                            <h3 class="text-center text-light">Rango</h3>
                                        </div>
                                        
                                    </div>
                                    <div class="text-center col justify-content-center">
                                        {{-- @if (env('APP_ENV') == 'local') --}}
                                            @if ($user->range->id == 1 )
                                                <img src="{{ asset('/img/bronce.svg') }}" alt="tag" class="m-2 w-50">
                                            @elseif($user->range->id == 2 )
                                                <img src="{{ asset('/img/plata.svg') }}" alt="tag" class="m-2 w-50">
                                            @elseif($user->range->id == 3 )
                                                <img src="{{ asset('/img/oro.svg') }}" alt="tag" class="m-2 w-50">
                                            @elseif($user->range->id == 4 )
                                                <img src="{{ asset('/img/platino.svg') }}" alt="tag" class="m-2 w-50">
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
                                        <h4 class="text-center text-light">{{$user->range->name}}</h4>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="mb-3 card banner">
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
                {{-- <input type="text" class="form-control" aria-label="Default" name="times" id="times"
                    aria-describedby="inputGroup-sizing-default" value="{{ $times }}" style="display: none"> --}}
                @include('share')
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
