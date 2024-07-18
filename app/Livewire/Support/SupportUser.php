<?php

namespace App\Livewire\Support;

use App\Services\UserService;
use Livewire\Component;

class SupportUser extends Component
{
    public $userId;
    public $user;
    public $url;
    public $channel;
    private $userService;

    public function mount($user_id)
    {
        $this->userId = $user_id;
        $this->userService = new UserService();
    }
    public function render()
    {
        $this->url = 'https://player.twitch.tv/?channel=lucho952000&parent=www.comunidadnc.com';
        $this->channel = 'lucho952000';
        $this->user = $this->userService->getById($this->userId);
        if(isset($this->user)){
            $this->url = 'https://player.twitch.tv/?channel='.$this->user->channel.'&parent=www.comunidadnc.com';
            $this->channel = $this->user->channel;
        }

        return view('livewire.support.support-user');
    }
}
