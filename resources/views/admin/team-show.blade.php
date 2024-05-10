<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{$team->name}}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <livewire:datatable.user-table :team="$team" />
            </div>
        </div>
    </div>
   
</x-app-layout>