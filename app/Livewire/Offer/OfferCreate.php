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
            'offer.link' => 'required|string|max:255',
            'offer.offer_coins' => 'required|string|max:255',
            'offer.price' => 'required|string|max:255',
        ],[
            'offer.link.required' => 'El campo de url es obligatorio.',
            'offer.link.string' => 'El campo de url debe ser una cadena de texto.',
            'offer.link.max' => 'El campo de url no debe exceder los 255 caracteres.',

            'offer.viewers.required' => 'El campo de seguidores es obligatorio.',
            'offer.viewers.string' => 'El campo de seguidores debe ser una cadena de texto.',
            'offer.viewers.max' => 'El campo de seguidores no debe exceder los 255 caracteres.',

            'offer.offer_coins.required' => 'El campo de neocoins de la oferta es obligatorio.',
            'offer.offer_coins.string' => 'El campo de neocoins de la oferta debe ser una cadena de texto.',
            'offer.offer_coins.max' => 'El campo de neocoins de la oferta no debe exceder los 255 caracteres.',

            'offer.price.required' => 'El campo de precio es obligatorio.',
            'offer.price.string' => 'El campo de precio debe ser una cadena de texto.',
            'offer.price.max' => 'El campo de precio no debe exceder los 255 caracteres.',
        ]);

        $offer = [];
        $this->offerService = new OfferService();
        $offer['link'] = $this->offer['link'];
        $offer['viewers'] = $this->offer['viewers'];
        $offer['offer_coins'] = $this->offer['offer_coins'];
        $offer['price'] = $this->offer['price'];
        $offer['user_id'] = $this->user_id;

        $created = $this->offerService->create($offer);
        if($created){
            return redirect()->route('offer-index');
        }

    }
    public function return(){
        return redirect()->route('summary');
    }

}
