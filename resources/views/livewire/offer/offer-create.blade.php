<div>
    <div class="d-flex justify-content-center">
        <div class="mb-4 card bg-secondary profile">

            <div class="p-0 m-0 card-body bg-secondary">
                <div class="bg-black card ">
                    <h5 class="mt-2 text-center card-title text-light ">Crear Oferta</h5>
                </div>

                    <div class="row justify-content-center">
                        <div class="col-8">
                            <label for="name">viewers</label>
                            <input type="number" id="name" wire:model="offer.viewers" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">
                            @error('offer.viewers') <span class="text-danger position-absolute">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-8">
                            <label for="name">offer_coins</label>
                            <input type="number" id="name" wire:model="offer.offer_coins" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">
                            @error('offer.offer_coins') <span class="text-danger position-absolute">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-8">
                            <label for="name">price</label>
                            <input type="number" id="name" wire:model="offer.price" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">
                            @error('offer.price') <span class="text-danger position-absolute">{{ $message }}</span> @enderror
                        </div>

                        <div class="mt-3 mb-3 col-8">
                            <button type="button" class="btn btn-danger" wire:click="createOffer">Crear</button>
                        </div>
                    </div>

            </div>
        </div>
    </div>
</div>
