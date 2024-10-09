<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Usuarios') }}
        </h2>
        <livewire:user-search />
        @include('layouts.temporary-buttons')
    </x-slot>

    {{-- {{ Auth::user()->name }} --}}
    {{-- @dump( Auth::user()) --}}
    {{-- <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                <x-welcome />
            </div>
        </div>
    </div> --}}
    {{-- @livewire('datatable.user-table') --}}
    <div class="container">
        <div class="row">

                <div class="col " style="margin-top: 155px">
                    <a href="{{route('actions-teams-platform',['platform_id' => 1])}}"><img src="{{asset('img/twich_logo.png')}}" alt="" width="60%"></a>
                </div>
                <div class="col" style="margin-top: 40px">
                    <a href="{{route('actions-teams-platform',['platform_id' => 2])}}"><img src="{{asset('img/trovo_logo.svg')}}" alt="" width="100%"></a>
                </div>
                <div class="col" style="margin-top: 150px">
                    <a href="#"><img src="{{asset('img/kick_logo.png')}}" alt="" width="100%"></a>

                </div>

        </div>
    </div>

</x-app-layout>
