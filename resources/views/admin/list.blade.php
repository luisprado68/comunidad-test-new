<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Usuarios') }}
        </h2>
        <livewire:user-search />
        @include('layouts.temporary-buttons')
    </x-slot>
    <div class="container">
        <div class="row">
            <div class="col-12">

                @livewire('datatable.user-table', ["model" => User::class, "userService" => null])
            </div>
        </div>
    </div>

</x-app-layout>
