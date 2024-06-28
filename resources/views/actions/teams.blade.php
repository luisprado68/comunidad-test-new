<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Equipos') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row">
            <div class="col-12">
                @livewire('datatable.team-table', ["model" => Team::class, "userService" => null,"platform_id" => $platform_id])

            </div>
        </div>
    </div>

</x-app-layout>
