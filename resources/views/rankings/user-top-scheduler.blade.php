<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Usuarios Top de con puntaje') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <livewire:datatable.user-top-schedulers-table />
               {{-- @livewire('datatable.user-top-score-table', ["model" => User::class, "userService" => null])>
               @livewire('datatable.user-top-score-table', ["model" => User::class, "userService" => null])> --}}
            </div>
        </div>
    </div>
   
</x-app-layout>
