<div  style="margin-bottom: 100px">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(session('message'))
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <div id="liveToast" class="toast text-bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    {{--                                    <img src="..." class="rounded me-2" alt="...">--}}
                    <strong class="me-auto">Notificación</strong>
                    {{--                                    <small>11 mins ago</small>--}}
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    {{session('message')}}
                </div>
            </div>
        </div>

    @endif
            <div class="container">
                <div class="row">
                <div class=" col-lg-8 col-sm-12 mt-4">
                    <div class="row">

                        <div class="iframe-container border border-5 col-sm-12 chat-size" >
                            <iframe
                                src="{{$url}}"
                                allowfullscreen>
                            </iframe>
                        </div>
                        <div class=" col-sm-12 mt-4">
                            <div class="card text-center ">
                                <div class="card-body banner-twich">
                                    <div class="row">
                                        <div class="col-lg-2 col-sm-8">
                                            <img src="{{$user_stream->img_profile}}" alt="tag" class="profile-img-item rounded-circle">
                                        </div>
                                        <div class="col-lg-8 col-sm-12">
                                            <h5 class="card-title text-center text-light">{{$channel}}</h5>
                                        </div>
                                    </div>





                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-4 col-sm-12 mt-4" >
                    <div class="row">

                        <div class="iframe-container-chat col-sm-12 chat-size chat-right">
                            <iframe id="twitch-chat-embed"
                                    src="{{$url_chat}}"
                                    height="500"
                                    allowfullscreen>
                            </iframe>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <div class="card text-center ">
                                <div class="card-body banner-twich">
{{--                                    <h5 class="card-title text-light">Cantidad de tiempo</h5>--}}
{{--                                    <h6 class="card-subtitle mb-2  text-light" id="timer">00:00</h6>--}}
                                    <button type="button" class="btn btn-warning my-2" wire:click="validateTime">Termine de ver</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                </div>
            </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toastLiveExample = document.getElementById('liveToast');
        const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample);
        toastBootstrap.show();
    </script>
    @livewireScripts
</div>
