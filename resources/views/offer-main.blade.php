@extends('layouts.main')


@section('content')
    <div class="container">
        <div class="row">
            @if (session()->has('user'))
                @if($section == "index")
                    @livewire('offer.offer-list')
                @elseif($section == "create")
                    @livewire('offer.offer-create',['user_id' => $user_id])
                @endif
            @endif

        </div>


@endsection
