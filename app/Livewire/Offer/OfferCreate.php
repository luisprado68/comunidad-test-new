<?php

namespace App\Livewire\Offer;


use App\Models\Offer;
use App\Services\OfferService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class OfferCreate extends Component
{
    public $offer;
    public $user_id;
    private $offerService;

    public function mount()
    {
        $this->offer = [
            'viewers' => '',
            'offer_coins' => '',
            'price' => '',
        ];
    }
    public function render()
    {

        Log::debug('render ');
        return view('livewire.offer.offer-create');
    }
    public function createOffer(){

        $this->validate([
            'offer.viewers' => 'required|string|max:255',
            'offer.offer_coins' => 'required|string|max:255',
            'offer.price' => 'required|string|max:255',
        ]);

        $offer = [];
        $this->offerService = new OfferService();
        $offer['viewers'] = $this->offer['viewers'];
        $offer['offer_coins'] = $this->offer['offer_coins'];
        $offer['price'] = $this->offer['price'];
        $offer['user_id'] = $this->user_id;

        $created = $this->offerService->create($offer);
        if($created){
            return redirect()->route('offer-index');
        }

    }
}
