@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            @if (session()->has('user') && session('status') == 0)
                @include('link')
            @else
                @include('status', ['user' => $user])
                <div class="pt-1 col-md-12 w-100">
                    <div class="card bg-secondary">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card ">
                                        <div class="card-body text-start text-dark">
                                            <p>Esta <b>PROHIBIDO</b> los siguientes comentarios en los chats ya que son
                                                motivo
                                                de baneo ya sea temporal o definitivo: Follow x follow, f4f, fxf, te
                                                sigo y
                                                me
                                                sigues, ayúdame a crecer, vengo del grupo o cualquier comentario dando a
                                                conocer
                                                el grupo.
                                            </p>
                                            <p><b>RECUERDA</b> este no es un grupo de FXF.</p>
                                            <p>Tu stream debe estar encendido y evitando problemas técnicos minimo 10
                                                minutos
                                                <b>ANTES</b> de la hora.
                                            </p>
                                            <p><b>PROHIBIDO</b> chat en modo solo seguidores. Si al entrar hay chat modo
                                                solo
                                                seguidores, no se les dara follow ni dar los puntos.</p>
                                            <p><b>PROHIBIDO</b> cerrar directo o enviar raid ANTES de que finalice la
                                                hora.
                                            </p>
                                            <p>Si los usuarios no puede chatear libremente, nos reservamos el derecho de
                                                cancelar el apoyo sin devolución de puntos.</p>
                                            <p>Se vera vera en la hora 2 streames del mismo grupo sea A, B, C.</p>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body ">
                            <div class="row">

                                <div class="py-2  col-12">
                                    <div class="card banner">
                                        <div class="card-body text-start">
                                            <h3 class="text-center text-light">Mi Agenda Semanal</h3>
                                        </div>
                                    </div>
                                </div>
                                @if (count($week) > 0)
                                    <div class="mt-2 card bg-dark text-light">
                                        <div class="row">

                                            @foreach ($week as $key => $days)
                                                <div class="my-4 col-lg-4 col-md-4 col-sm-12">
                                                    <input class="text-center form-control form-control-lg bg-warning"
                                                        type="text" placeholder="{{ trans('user.create.' . $key) }}"
                                                        disabled>

                                                    @foreach ($days['times'] as $time)
                                                        <input class="text-center form-control form-control-lg bg-light"
                                                            type="text" placeholder="{{ $time }}" disabled>
                                                    @endforeach



                                                </div>
                                            @endforeach




                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
                {{-- <input type="text" class="form-control" aria-label="Default" name="times" id="times"
                    aria-describedby="inputGroup-sizing-default" value="{{ $times }}" style="display: none"> --}}
            @endif


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
