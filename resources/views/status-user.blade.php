<div class="mb-3 col-md-12 w-100">
    <div class="card banner-border">

        <div class="card-body banner-user">
            <div class="row">
               
                <div class="offset-lg-1 col-lg-7 col-md-4 col-sm-10">
                    <div class="row">
                        <div class="mb-3 text-center col-lg-3 col-sm-12">
                            @if (isset($user->img_profile) && $user->img_profile != '')
                                <img src="{{$user->img_profile}}" alt="tag"
                                class="logo rounded-circle">
                                @else
                                <img src="{{asset('/img/logo.webp')}}" alt="tag"
                                class="logo rounded-circle">
                            @endif
                            
                            
                        </div>
                        <div class="text-center col-lg-8 text-light">
                            <h3 class="">{{$user->channel}}</h3>
                            <p>{{$user->description}}</p>
                           
                        </div>
                    </div>

                </div>
                <div class="col-lg-2 col-md-4 col-sm-12 offset-lg-2">
                    <div class="text-center col-lg-1 col-sm-1">
                        @if(isset($user->instagram) && $user->instagram != '')
                            <a href="{{$user->instagram}}" target="_blank"><i class="bi bi-instagram color-instagram"></i></a>
                        @else
               
                            <i class="bi bi-instagram color-instagram"></i>
                            
                        @endif
                    </div>
                    <div class="text-center col-lg-1 col-sm-4">
                        @if(isset($user->facebook) && $user->facebook != '')
                        <a href="{{$user->facebook}}" target="_blank"><i class="bi bi-tiktok color-facebook"></i></a>
                        @else
                        <i class="bi bi-tiktok color-facebook"></i>
                        @endif
                    </div>
                    <div class="text-center col-lg-1 col-sm-4">
                        @if(isset($user->youtube) && $user->youtube != '')
                        <a href="{{$user->youtube}}" target="_blank"><i class="bi bi-youtube color-youtube"></i></a>
                        @else
                        <i class="bi bi-youtube color-youtube"></i>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>