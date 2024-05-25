<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Equipos') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row">
            <div class="col-12">
                {{-- @dump(Auth::user()->team->getRoleNames()) --}}

                {{-- @dump(Auth::user()->getRoleNames()) --}}

                @livewire('datatable.team-table', ["model" => Team::class, "userService" => null])

            </div>
        </div>
    </div>

</x-app-layout>
