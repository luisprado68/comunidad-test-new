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

        {{--document.addEventListener('DOMContentLoaded', (event) => {--}}
        {{--    function getCsrfToken() {--}}
        {{--        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');--}}
        {{--    }--}}
        {{--    var user_stream = @json($user_stream->id);--}}
        {{--    var user_model_id = @json($user_model_id);--}}
        {{--    var url_summary =  @json($url_summary);--}}
        {{--    var url_support =  @json($url_support);--}}
        {{--    var url_plus_point = @json($url_plus_point);--}}
        {{--    var minute_initial = 45;--}}
        {{--    function sendSynchronousRequest() {--}}

        {{--        var xhr = new XMLHttpRequest();--}}
        {{--        var data = JSON.stringify({--}}
        {{--            user_streaming: user_stream,--}}
        {{--            user_viewer: user_model_id--}}
        {{--        });--}}

        {{--        xhr.open("POST", url_plus_point, false); // false makes the request synchronous--}}
        {{--        xhr.setRequestHeader("Content-Type", "application/json");--}}
        {{--        xhr.setRequestHeader("X-CSRF-TOKEN", getCsrfToken());--}}

        {{--        xhr.onload = function() {--}}
        {{--            if (xhr.status >= 200 && xhr.status < 300) {--}}
        {{--                console.log("Response:", xhr.responseText);--}}
        {{--                window.close();--}}
        {{--            } else {--}}
        {{--                console.error("Request failed with status:", xhr.status);--}}
        {{--            }--}}
        {{--        };--}}

        {{--        xhr.onerror = function() {--}}
        {{--            console.error("Request failed");--}}
        {{--        };--}}

        {{--        xhr.send(data);--}}

        {{--        // Log response directly after send() due to synchronous nature--}}
        {{--        console.log("Response after send:", xhr.responseText);--}}
        {{--    }--}}
        {{--    // URL específica a validar--}}
        {{--    const targetUrl = url_support+ '/' + user_stream;--}}
        {{--    console.log(targetUrl)--}}
        {{--        // Identificador único para la pestaña actual--}}
        {{--    const tabId = Math.random().toString(36).substr(2, 9);--}}
        {{--    console.log(window.location.href)--}}
        {{--        // Guardar el estado de la pestaña actual en localStorage--}}
        {{--        localStorage.setItem('tabId', tabId);--}}
        {{--        localStorage.setItem('tabUrl', window.location.href);--}}

        {{--        // Función para verificar si hay otra pestaña abierta con la misma URL--}}
        {{--        function checkDuplicateTabs() {--}}
        {{--            const storedTabId = localStorage.getItem('tabId');--}}
        {{--            const storedTabUrl = localStorage.getItem('tabUrl');--}}

        {{--            if (storedTabUrl === targetUrl) {--}}
        {{--                if (storedTabId !== tabId) {--}}
        {{--                    console.log('alerta para cerrar las pestañas duplicadas');--}}
        {{--                }else{--}}
        {{--                    //--}}
        {{--                    var minute = parseInt(localStorage.getItem('minutes_'+user_stream)) || minute_initial;--}}
        {{--                    if(minute < minute_initial){--}}
        {{--                        //fix para evitar que baje al recargar la pagina--}}
        {{--                        minute = minute + 1;--}}
        {{--                    }--}}

        {{--                    // var minute = 50;--}}
        {{--                    var countDownDate = new Date().getTime() + minute * 60 * 1000;--}}

        {{--                    var x = setInterval(function() {--}}

        {{--                        const now_time = new Date();--}}
        {{--                        const now_minutes = now_time.getMinutes();--}}
        {{--                        const now_seconds = now_time.getSeconds();--}}
        {{--                        // Verifica si es el minuto y segundo cero--}}
        {{--                        if (now_minutes === 0 && now_seconds === 0) {--}}
        {{--                            window.location.href = url_summary; // Cambia la URL a la que quieres redirigir--}}
        {{--                        }--}}

        {{--                        var now = new Date().getTime();--}}
        {{--                        var distance = countDownDate - now;--}}
        {{--                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));--}}
        {{--                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);--}}


        {{--                        localStorage.setItem('minutes_'+user_stream, minutes.toString());--}}
        {{--                        document.getElementById("timer").innerHTML = minutes + "m " + seconds + "s ";--}}

        {{--                        if (distance < 0) {--}}
        {{--                            clearInterval(x);--}}

        {{--                            document.getElementById("timer").innerHTML = "EXPIRED";--}}
        {{--                            localStorage.removeItem('minutes_'+user_stream);--}}
        {{--                            // Redirigir a una URL específica--}}
        {{--                            sendSynchronousRequest();--}}
        {{--                        }--}}
        {{--                    }, 1000);--}}
        {{--                }--}}
        {{--            }--}}
        {{--        }--}}

        {{--    // Verificar al cargar la página--}}
        {{--                checkDuplicateTabs();--}}

        {{--    // Escuchar cambios en el almacenamiento local--}}
        {{--                window.addEventListener('storage', function(event) {--}}
        {{--                    if (event.key === 'tabId' || event.key === 'tabUrl') {--}}
        {{--                        checkDuplicateTabs();--}}
        {{--                    }--}}
        {{--                });--}}

        {{--    // Limpiar el estado cuando la pestaña se cierra--}}
        {{--                window.addEventListener('beforeunload', function() {--}}
        {{--                    localStorage.removeItem('tabId');--}}
        {{--                    localStorage.removeItem('tabUrl');--}}
        {{--                });--}}

        {{--});--}}

        window.addEventListener("beforeunload", function (e) {
            var data = JSON.stringify({
                user_streaming:@json($user_stream->id),
                user_viewer:  @json($user_model_id)
            });
            // Opcional: enviar una petición AJAX al servidor
            fetch("http://127.0.0.1:8000/support/plus_point", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: data
            })
                .then(response => {
                    if (response.status >= 200 && response.status < 300) {
                        if(response.text() === 'ok'){

                            window.close();
                        }else{
                        }

                        // return response.text(); // O response.json() si la respuesta es JSON
                    } else {
                        throw new Error('Error en la solicitud: ' + response.statusText);
                    }
                })
                .then(data => {
                    // window.close();
                    console.log(data); // Aquí manejas la respuesta (debería ser 'ok')
                })
                .catch(error => {
                    console.error('Hubo un problema con la solicitud:', error);
                });
        });
    </script>
    @livewireScripts
</div>
