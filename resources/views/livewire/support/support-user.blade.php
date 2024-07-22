<div >

            <div class="container">
                <div class="row">
                <div class=" col-lg-8 col-sm-12 mt-4">
                    <div class="row">
                        <div class=" col-sm-12">
                            <div class="card text-center ">
                                <div class="card-body banner-twich">
                                    <div class="d-flex justify-content-between">
                                        <img src="{{$user_stream->img_profile}}" alt="tag" class="profile-img rounded-circle">
                                        <h5 class="card-title text-light">{{$channel}}</h5>
                                        <h6 class="card-subtitle mb-2 text-light" style="visibility: hidden">Streamer</h6>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="iframe-container border border-5 col-sm-12 chat-size" >
                            <iframe
                                src="{{$url}}"
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>

                </div>
                <div class="col-lg-4 col-sm-12 mt-4" >
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card text-center ">
                                <div class="card-body banner-twich">
                                    <h5 class="card-title text-light">Cantidad de tiempo</h5>
                                    <h6 class="card-subtitle mb-2  text-light">00:00</h6>
{{--                                    <h6 class="card-subtitle mb-2  text-light" id="timer">00:00</h6>--}}
                                </div>
                            </div>
                        </div>
                        <div class="iframe-container-chat col-sm-12 chat-size chat-right">
                            <iframe id="twitch-chat-embed"
                                    src="{{$url_chat}}"
                                    height="500"
                                    allowfullscreen>
                            </iframe>
                        </div>

                    </div>
                </div>


                </div>
            </div>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            var countDownDate = new Date().getTime() + 5 * 60 * 1000;

            var x = setInterval(function() {
                var now = new Date().getTime();
                var distance = countDownDate - now;

                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("timer").innerHTML = minutes + "m " + seconds + "s ";

                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("timer").innerHTML = "EXPIRED";
                }
            }, 1000);
        });
    </script>

    @livewireScripts
</div>
