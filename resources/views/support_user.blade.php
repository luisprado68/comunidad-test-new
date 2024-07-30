@extends('layouts.main')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container">
        <div class="row">

            @if (session()->has('user') && session('status') == 0 || !isset($user_model->team))
                @include('link')
            @else
                @include('status', ['user' => $user_model])
                @livewire('support.support-user',['user_id' => intval($id),'user_model_id'=> $user_model->id])

            @endif

        </div>
    </div>
@endsection
@push('chatters')
    {{-- @if (env('APP_ENV') == 'local')
        <script src="{{ asset('/js/setTime.js') }}"></script>
    @else
        <script src="./public/js/setTime.js"></script>
    @endif --}}
@endpush
