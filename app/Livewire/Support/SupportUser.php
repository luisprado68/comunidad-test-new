<?php

namespace App\Livewire\Support;

use App\Services\UserService;
use Livewire\Component;

class SupportUser extends Component
{
    public $userId;
    public $user_model_id;
    public $user_stream;
    public $url;
    public $url_chat;
    public $channel;
    public $url_plus_point;
    public $url_summary;
    public $minimum_time;
    public $url_support;
    private $userService;

    public function mount($user_id)
    {
        $this->userId = $user_id;
        $this->userService = new UserService();
        $this->url_plus_point = env('APP_URL') . 'support/plus_point';
        $this->url_summary = env('APP_URL') . 'summary';
        $this->url_support = env('APP_URL') . 'support';
        $this->minimum_time = env('TIME_MINIMUN_TO_PLUS_POINT');
    }
    public function render()
    {
        $this->url = 'https://player.twitch.tv/?channel=lucho952000&parent=www.neo-community.com';
        $this->url_chat = 'https://www.twitch.tv/embed/lucho952000/chat?parent=www.neo-community.com';
        $this->channel = 'lucho952000';
        $this->user_stream = $this->userService->getById($this->userId);
        if(isset($this->user_stream)){
            $this->url = 'https://player.twitch.tv/?channel='.$this->user_stream->channel.'&parent=www.neo-community.com';
            $this->url_chat = 'https://www.twitch.tv/embed/'.$this->user_stream->channel.'/chat?parent=www.neo-community.com';
            $this->channel = $this->user_stream->channel;
        }

        return view('livewire.support.support-user');
    }
}
