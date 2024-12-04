<div>
    <div class="d-flex justify-content-center">
        <div class="mb-4 card bg-secondary profile">

            <div class="p-0 m-0 card-body bg-secondary">
                <div class="bg-black card ">
                    <h5 class="mt-2 text-center card-title text-light ">Crear Oferta</h5>
                </div>

                    <div class="row justify-content-center">
                        <div class="col-8 mb-4 mt-4">
                            <label for="name" class="text-light">Link de video</label>
                            <input type="text" id="name" wire:model="offer.link" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">
                            @error('offer.link') <span class="text-danger position-absolute">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-8 mb-4">
                            <label for="name" class="text-light">Cuantos vistas deseas?</label>
                            <input type="number" id="name" wire:model="offer.viewers" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">
                            @error('offer.viewers') <span class="text-danger position-absolute">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-8 mb-4">
                            <label for="name" class="text-light">Cuantas coins ofreces por vista?</label>
                            <input type="number" id="name" wire:model="offer.offer_coins" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">
                            @error('offer.offer_coins') <span class="text-danger position-absolute">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-8 mb-4" >
                            <label for="name" class="text-light">Costo total</label>
                            <input type="number" id="name" wire:model="offer.price" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">
                            @error('offer.price') <span class="text-danger position-absolute">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-8 mb-4 bg-primary rounded" >
                            <div class="m-3">
                                <h6 class="text-light">
                                    Cuanto mas Neo Coins por minuto, más rapido se completara tu oferta.
                                </h6>
                                <h6>
                                    Necesitas más neo coins? Gana neocoins gratis <a href="#" style="color: #ffffff">Aqui</a> o consiguelas rapidamente comprandolas.
                                </h6>
                            </div>

                        </div>

                        <div class="mt-3 mb-3 col-8">
                            <button type="button" class="btn btn-primary" wire:click="createOffer">Crear</button>
                            <button type="button" class="btn btn-danger" wire:click="return">Volver</button>
                        </div>
                    </div>

            </div>
        </div>
    </div>
</div>
