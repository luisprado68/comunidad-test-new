<?php

namespace App\Livewire\Layouts;

use App\Models\User;
use Illuminate\Database\Console\DumpCommand;
use Livewire\Component;

class Nav extends Component
{
    public $search = '';
    public $list = [];
    public $show_result = false;

    public function render()
    {
        // dump($this->show_result);
        return view('livewire.layouts.nav');
    }

    public function setSearch(){
        
        
        if(isset($this->search) && $this->search != ''){
            $this->show_result = true;
            $this->list = User::where('channel', 'like', '%'.$this->search.'%')->where('deleted',0)->orWhere('name', 'like', '%'.$this->search.'%')->take(5)->get();
        }
    }
    public function closeResult(){
        $this->show_result = false;
    }
}
