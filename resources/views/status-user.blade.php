<div class="mb-3 col-md-12 w-100">
    <div class="card bg-info">

        <div class="card-body banner">
            <div class="row">
               
                <div class="col-lg-6 col-md-4 col-sm-10">
                    <div class="row">
                        <div class="mb-3 col ">
                            <img src="https://static-cdn.jtvnw.net/jtv_user_pictures/6471351b-ea90-4cd2-828b-406a7dea08e1-profile_image-300x300.png" alt="tag"
                            class="logo rounded-circle">
                            
                        </div>
                        <div class="col">
                            <h3>{{$user->channel}}</h3>
                            <p>asdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdas</p>
                           
                        </div>
                    </div>

                </div>
                <div class="col-lg-2 col-md-4 col-sm-2 offset-lg-2">
                    <div class="col ">
                        @if(isset($user->instagram) && $user->instagram != '')
                            <a href="{{$user->instagram}}" target="_blank"><i class="bi bi-instagram" style="font-size: 1.4em"></i></a>
                        @else
                        <i class="bi bi-instagram" style="font-size: 1.4em;color: gray"></i>
                        @endif
                    </div>
                    <div class="col ">
                        @if(isset($user->facebook) && $user->facebook != '')
                        <a href="{{$user->facebook}}" target="_blank"><i class="bi bi-facebook" style="font-size: 1.4em"></i></a>
                        @else
                        <i class="bi bi-instagram" style="font-size: 1.4em;color: gray"></i>
                        @endif
                    </div>
                    <div class="col ">
                        @if(isset($user->youtube) && $user->youtube != '')
                        <a href="{{$user->youtube}}" target="_blank"><i class="bi bi-youtube" style="font-size: 1.4em"></i></a>
                        @else
                        <i class="bi bi-instagram" style="font-size: 1.4em;color: gray"></i>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
