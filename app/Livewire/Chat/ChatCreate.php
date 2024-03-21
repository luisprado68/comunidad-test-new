<?php

namespace App\Livewire\Chat;

use Livewire\Component;

class ChatCreate extends Component
{
    protected $layout = 'components.layouts.app';
    public function render()
    {
        return view('livewire.chat.chat-create');
    }
}
