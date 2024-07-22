<?php

namespace App\Livewire\Support;

use App\Services\UserService;
use Livewire\Component;

class SupportUser extends Component
{
    public $userId;
    public $user_stream;
    public $url;
    public $url_chat;
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
        $this->url_chat = 'https://www.twitch.tv/embed/lucho952000/chat?parent=www.comunidadnc.com';
        $this->channel = 'lucho952000';
        $this->user_stream = $this->userService->getById($this->userId);
        if(isset($this->user_stream)){
            $this->url = 'https://player.twitch.tv/?channel='.$this->user_stream->channel.'&parent=www.comunidadnc.com';
            $this->url_chat = 'https://www.twitch.tv/embed/'.$this->user_stream->channel.'/chat?parent=www.comunidadnc.com';
            $this->channel = $this->user_stream->channel;
        }

        return view('livewire.support.support-user');
    }
}
