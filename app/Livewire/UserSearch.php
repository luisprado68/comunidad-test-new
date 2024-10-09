<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class UserSearch extends Component
{
    public $search;  // Variable que almacena el término de búsqueda
    public $users;
    public $user;
    public $list = [];
    public $show_result = false;
    public function render()
    {
//        $this->users = User::where('channel', 'like', '%' . $this->search . '%')
//            ->limit(5)
//            ->get();
//       Log::debug('$users --- ' . json_encode($this->users));
        return view('livewire.user-search');
    }

    public function setSearch(){

        $this->show_result = false;
        if(isset($this->search) && $this->search != ''){
            $this->show_result = true;
            $this->list = User::

                where('channel', 'like', '%'.$this->search.'%')
                ->where('status',1)
                ->where('deleted',0)->take(5)->get();
        }
        if(count($this->list) == 0){
            $this->show_result = false;
        }
    }
    public function closeResult(){
        $this->show_result = false;
    }
}
