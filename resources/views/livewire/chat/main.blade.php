<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Chat Main') }}
        </h2>
    </x-slot>

    <div class="chat_container">
        @livewire('chat.chat-list')
    </div>
    <div class="chat_box_conteiner">
        @livewire('chat.chatbox')

        @livewire('chat.send-message')
    </div>

</div>
