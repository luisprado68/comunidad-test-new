<div class="pt-1 col-md-12 w-100" style="margin-bottom: 40px">
    <div class="card bg-secondary">
        <div class="card-body footer text-light ">
            <h5>COMPARTE TU LINK CON UN AMIGO</h5>
            <p>Comparte este enlace con un colega streamer y gana 1 punto cuando él haga su primer directo, recuerda 25 puntos de referido más rango ORO pasas a rango PLATINO.</p>
            <div class="mb-3 input-group">
                <input type="text" class="form-control" value="{{ route('referrer', ['user_name' => $user->channel]) }}"
                    aria-label="Recipient's username" aria-describedby="basic-addon2" id="myInput" disabled>
                <div class="input-group-append">
                    <button class="btn bg-dark text-light" type="button" onclick="copy()">Copiar</button>
                </div>
            </div>
        </div>
    </div>
    
</div>
@push('copyText')

    {{-- @if (env('APP_ENV') == 'local') --}}
        <script src="{{ asset('/js/copytext.js') }}"></script>
    {{-- @else
        <script src="./public/js/copytext.js"></script>
    @endif --}}

    

@endpush