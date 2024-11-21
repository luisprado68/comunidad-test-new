<?php

namespace App\Livewire\Site;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class Score extends Component
{
    use WithPagination;
    public $ref;
    public $user;
    public $search = '';

    public function boot()
    {
        Paginator::useBootstrap();
    }
    public function updatingSearch()
    {
        // Resetea la página a la primera cuando se actualiza el término de búsqueda
        $this->resetPage();
    }
    public function render()
    {
        Log::debug('$search --- ' . $this->search);
        $users = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->paginate(10);
        return view('livewire.site.score',['users' => $users]);
    }
}
