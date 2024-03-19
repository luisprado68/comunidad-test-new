<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Usuarios') }}
        </h2>
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
            <div class="col-12">
                <livewire:datatable.user-table  />
            </div>
        </div>
    </div>
   
</x-app-layout>
