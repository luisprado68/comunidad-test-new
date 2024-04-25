<?php

namespace App\Livewire\Layouts;


use App\Models\User;
use Illuminate\Database\Console\DumpCommand;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Nav extends Component
{
    public $search = '';
    public $list = [];
    public $show_result = false;
    public $user;

    public function render()
    {
        // dump($this->show_result);
        return view('livewire.layouts.nav');
    }

    public function setSearch(){
        
        $this->show_result = false;
        if(isset($this->search) && $this->search != ''){
            $this->show_result = true;
            $this->list = User::
            where('channel', 'like', '%'.$this->search.'%')
            ->where('deleted',0)->take(5)->get();

            // $this->list = User::where('twich_id','!=',$this->user['id'])
            // ->where('channel', 'like', '%'.$this->search.'%')
            // ->where('deleted',0)->take(5)->get();
        }
        Log::debug("list " . $this->list);
        if(count($this->list) == 0){
            $this->show_result = false;
        }
    }
    public function closeResult(){
        $this->show_result = false;
    }
}
