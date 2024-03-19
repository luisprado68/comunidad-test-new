<x-app-layout>

    {{-- <x-maz-config-header
        title="{{ trans('branch.index.title') }}"
        subtitle="{{ trans('branch.index.subtitle') }}" />
    
    @if (session('deleted') == 'success')
        <x-maz-alert-message
            message="{{ trans('branch.create.delete-success') }}"
            icon="bi bi-check-circle"
            type="success"
        />
    @endif
    @if (session('ordered') == 'success')
    <x-maz-alert-message
        message="{{ trans('branch.create.order-success') }}"
        icon="bi bi-check-circle"
        type="success"
    />
    @endif --}}
   
   
    
    <section class="section">
        {{-- @if($action == 'index')
            <!-- Livewire branch list -->
            @livewire('admin.admin-list')
        @endif --}}

        @if($action == 'create')
            <!-- Livewire branch create -->
                @livewire('branch.branch-create', ['action' => $action])
        @endif

        @if($action == 'edit')
            <!-- Livewire branch edit -->
                @livewire('branch.branch-create', ['action' => $action, 'branchId' => $branchId])
        @endif

        @if($action == 'detail')
            <!-- Livewire branch detail -->
                @livewire('branch.branch-detail', ['action' => $action, 'branchId' => $branchId])
        @endif
        @if($action == 'index')
            <!-- Livewire branch detail -->
            @livewire('admin.admin-list', ['action' => $action])
        @endif
    </section>
</x-app-layout>
