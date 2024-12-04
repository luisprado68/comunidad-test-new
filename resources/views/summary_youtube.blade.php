<div class="pt-1 mb-4 col-md-12 w-100">
    <div class="card bg-dark">
        <div class="card-body ">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card banner-border">
                        <button type="button" class="btn btn-primary">Compra neocoins <i class="bi bi-bag-fill"></i></button>
                    </div>
                    <div class="card banner-border mt-2">
                        <div class="card-body bg-light rounded">
                            <h5 class="text-center text-dark">Impulsa el crecimiento de tu canal creando ofertas atractivas que capten la atención de tu audiencia.Estas estrategias haran crecer tu canal </h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card banner-border">
                        <button type="button" class="btn btn-primary"><i class="bi bi-coin"></i>
                            <a href="{{route('getCoins')}}" style="text-decoration: none;color:#ffffff">Gana neocoins gratis</a>
                            </button>
                    </div>
                    <div class="card banner-border mt-2">
                        <div class="card-body bg-light rounded">
                            <h5 class="text-center text-dark">Gana NeoCoins mirando los videos de los miembros  y usalos para crear ofertas y subir tus videos para hacer crecer tu canal</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card banner-border">
                    <button type="button" class="btn btn-primary"><a href="{{route('offer-create',['user_id' => $user->id])}}" style="text-decoration: none;color:#ffffff">Crear una oferta</a>
                        </button>
                    </div>
                    <div class="card banner-border mt-2">
                        <div class="card-body bg-light rounded">
                            <h5 class="text-center text-dark">Sube tus videos, define la cantidad de NeoCoins que quieres ofrecer por cada visualización, y observa cómo aumenta el interés de tu audiencia</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 mt-4">
                    <div class="card-body banner">
                        <h3 class="text-center text-light">Tu actividad reciente</h3>
                    </div>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">Fecha</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Neocoins</th>
                            <th scope="col">Balance</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row">11/10/24</th>
                            <td>mov</td>
                            <td>200</td>
                            <td>100</td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td colspan="2">Larry the Bird</td>
                            <td>@twitter</td>
                        </tr>
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
