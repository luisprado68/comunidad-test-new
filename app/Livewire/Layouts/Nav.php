<?php

namespace App\Livewire\Layouts;


use App\Enums\PlatformType;
use App\Models\User;
use Illuminate\Database\Console\DumpCommand;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Nav extends Component
{
    public $search;
    public $list = [];
    public $show_result = false;
    public $user;
    public $background;

    public function render()
    {
        if(session('user') && array_key_exists('platform_id',session('user'))){
            if( session('user')['platform_id'] == PlatformType::trovo){
                $this->background = 'banner-trovo';
            }else{
                $this->background = 'banner-twich';
            }

        }else{
            $this->background = 'banner-dark';
        }
        return view('livewire.layouts.nav');
    }

    public function setSearch(){

        $this->show_result = false;
        if(isset($this->search) && $this->search != ''){
            $this->show_result = true;
            $this->list = User::
            where('stream_id','!=',$this->user['id'])
            ->where('channel', 'like', '%'.$this->search.'%')
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
