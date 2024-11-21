<div>
    <div class="container">
        <div class="row">
{{--            @dump(session('user'))--}}
            @if (session()->has('user') && session('status') == 0 || !isset($user->team))
                @include('link')
            @else
                @if (isset($user_watched))
                    @include('status-user', ['user' => $user])
                @endif
                @if(session('message'))
                    <div class="toast-container position-fixed top-0 end-0 p-3">
                        <div id="liveToast" class="toast text-bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="toast-body">
                                {{session('message')}}
                            </div>
                        </div>
                    </div>

                @endif
                @include('status', ['user' => $user])

                @if($user->platform_id == \App\Enums\PlatformType::google)
                        <div class="card banner-border">
                            <div class="card-body banner">
                                <h3 class="text-center text-light">Gana Neo coins</h3>
                            </div>

                        </div>


                        <!-- Campo de búsqueda -->
{{--                        <input type="text" class="form-control mb-3" placeholder="Buscar usuarios..." wire:model="search">--}}

                        @forelse ($users as $user)
                            <div class="card m-3" style="width:20%">
                                <div class="card-body banner-youtube">
                                    <img src="{{$user->img_profile}}" class="profile-img rounded-circle" alt="...">
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">{{ $user->channel }}</h5>
{{--                                    <p class="card-text">{{ $user->description }}</p>--}}
                                    <a href="#" class="btn btn-primary">{{ $user->youtube }}</a>
                                </div>
                                <button type="button" class="btn btn-primary mb-2">Aceptar Oferta</button>
                            </div>

                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No se encontraron usuarios</td>
                            </tr>
                        @endforelse

                        <!-- Enlaces de paginación -->
                        <div class="mt-3">
                            {{ $users->links() }}
                        </div>
                @endif

{{--                @if (!isset($user_watched))--}}
{{--                    @include('share')--}}
{{--                @endif--}}

            @endif


        </div>
    </div>
</div>
