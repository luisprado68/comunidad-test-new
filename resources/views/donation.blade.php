@extends('layouts.main')


@section('content')
    <div class="container">
        <div class="row">

            <div class="pt-1 col-md-12 w-100">
                @if (session()->has('user') && session('status') == 0)
                    @include('link')
                @else
                    <div class="card bg-secondary">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-12 text-light">
                                    <h4 class="text-center text-light">Agradecemos tu apoyo</h4>
                                    <p>Nuestra comunidad fue creada sin intención de lucro alguno. Nuestro principal
                                        objetivo es ayudar a los streamers a ser parte de una comunidad que los ayuda a
                                        alcanzar sus metas y conocer nuevos streamers con los que puedan formar lazos,
                                        comunidades e interacciones más interesantes para su audiencia.</p>
                                    <p>Sin embargo nuestro sistema tiene detrás varias horas de trabajo, atención y mejoras
                                        constantes. Así como los costos mismos de servidores e infraestructura necesaria
                                        para mantener la comunidad funcionando.</p>
                                    <p>Como sugerencia de los propios miembros de la comunidad que quieren reconocer el
                                        trabajo que realizamos, ponemos a tu alcance esta opción donde puedes aportar para
                                        que este proyecto continúe creciendo y mejorando para todos.</p>
                                    <p><b>Queremos dejar muy claro que realizar una donación por este medio, NO OFRECE
                                            VENTAJA ALGUNA a ningún miembro de la comunidad, debido a que queremos que el
                                            funcionamiento de la misma sea equitativo y justo para todos.</b></p>
                                    <p>Toma en cuenta que si decides brindarnos tu apoyo, será de manera voluntaria y sin
                                        intención alguna de obtener beneficio personal alguno en la comunidad.</p>
                                    <p><b>Agradecemos muchísimo tu apoyo y esperamos seguir trabajando y mejorando para ti y
                                            cumplir correctamente tus expectativas</b></p>
                                    <p>Si tu economía personal, no te permite apoyarnos, no te preocupes, tu presencia y
                                        actividad en la comunidad ya nos ayuda muchísimo</p>
                                    <div class="text-center">

                                        <button type="button" class="btn btn-warning"><a
                                                href="https://liberapay.com/NeoCommunty/donate" target="_blank"
                                                style="text-decoration: none;color:black">Donar</a></button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="text" class="form-control" aria-label="Default" name="times" id="times"
                        aria-describedby="inputGroup-sizing-default" value="{{ $times }}" style="display: none">
                @endif
            </div>

        </div>
    </div>
    @include('layouts.footer')
@endsection
@push('chatters')
    {{-- @if (env('APP_ENV') == 'local')
        <script src="{{ asset('/js/setTime.js') }}"></script>
    @else
        <script src="./public/js/setTime.js"></script>
    @endif --}}
@endpush
