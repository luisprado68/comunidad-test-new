<?php

namespace App\Livewire\Offer;

use App\Services\OfferService;
use App\Services\UserService;
use Livewire\Component;

class OfferList extends Component
{
    private $userService;
    private $offerService;

    public function boot(UserService $userService,OfferService $offerService){
        $this->userService = $userService;
        $this->offerService = $offerService;
    }
    public function mount(){

//        dd($this->user);
    }
    public function render()
    {
//        $offers = $this->offerService->TableQuery();
        return view('livewire.offer.offer-list');
    }
}
