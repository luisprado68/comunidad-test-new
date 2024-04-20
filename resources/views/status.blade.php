<div class="col-md-12 w-100">
    <div class="card banner-border">

        <div class="card-body banner-dark">
            <div class="row">
                {{-- <div class="col-2">
                    @if (env('APP_ENV') == 'local')
                        <img src="{{ asset('/img/logo.webp') }}" alt="tag" class="profile-img">
                    @else
                        <img src="./public/img/logo.webp" alt="tag" class="profile-img">
                    @endif
                    <label class="text-light" for="">Score Día</label>
                </div> --}}
                <div class="col-lg-4 col-md-4 col-sm-10 offset-lg-2">
                    <div class="row">
                        <div class="mb-3 col ">
                            <label class="text-light" for="">Puntaje Día</label>
                            @if ($user->score)
                            <input class="text-center form-control form-control-lg bg-warning" type="text"
                            placeholder="{{$user->score->points_day . '/10'}}" disabled>
                            @else
                            <input class="text-center form-control form-control-lg bg-warning" type="text"
                            placeholder="{{ 0 . '/10'}}" disabled>
                            @endif
                            
                        </div>
                        <div class="col">
                            <label class="text-light" for="">Puntaje Semanal</label>
                            @if ($user->score)
                                <input class="text-center form-control form-control-lg bg-warning" type="text"
                                placeholder="{{$user->score->points_week  . '/60'}}" disabled>
                            @else
                                <input class="text-center form-control form-control-lg bg-warning" type="text"
                                placeholder="{{ 0 . '/60'}}" disabled>
                            @endif
                           
                           
                        </div>
                    </div>

                </div>
                <div class="col-lg-2 col-md-4 col-sm-2 offset-lg-2">
                    <div class="mb-3 input-group ">
                        <div class="input-group-prepend bg-dark">
                            <span class="input-group-text bg-primary text-light" id="basic-addon1">NeoCoins</span>
                        </div>
                        <input type="text" class="form-control" placeholder="{{isset($user->score->neo_coins) ? $user->score->neo_coins . '/500':'0/500'}}" aria-label="0"
                            aria-describedby="basic-addon1">
                    </div>
                    <div class="mb-3 input-group">
                        <div class="input-group-prepend bg-dark">
                            <span class="input-group-text bg-primary text-light" id="basic-addon1" style="font-size: 1rem;">Referidos</span>
                        </div>
                        <input type="text" class="form-control" placeholder="{{isset($user->points_support) ? $user->points_support . '/25':'0/25'}}" aria-label="Username"
                            aria-describedby="basic-addon1">
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
