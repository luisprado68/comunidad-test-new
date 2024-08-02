<div  style="margin-bottom: 100px">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                                    <h5 class="card-title text-light">Cantidad de tiempo</h5>
                                    {{--                                    <h6 class="card-subtitle mb-2  text-light">00:00</h6>--}}
                                    <h6 class="card-subtitle mb-2  text-light" id="timer">00:00</h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                </div>
            </div>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            function getCsrfToken() {
                return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            }
            var user_stream = @json($user_stream->id);
            var user_model_id = @json($user_model_id);
            var url_summary =  @json($url_summary);
            var url_support =  @json($url_support);
            var url_plus_point = @json($url_plus_point);
            // console.log(user_model_id);
            // console.log(user_stream);
            // console.log(url_plus_point);
            function sendSynchronousRequest() {

                var xhr = new XMLHttpRequest();
                // var url = "http://127.0.0.1:8000/support/plus_point";


                var data = JSON.stringify({
                    user_streaming: user_stream,
                    user_viewer: user_model_id
                });

                xhr.open("POST", url_plus_point, false); // false makes the request synchronous
                xhr.setRequestHeader("Content-Type", "application/json");
                xhr.setRequestHeader("X-CSRF-TOKEN", getCsrfToken());

                xhr.onload = function() {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        console.log("Response:", xhr.responseText);
                    } else {
                        console.error("Request failed with status:", xhr.status);
                    }
                };

                xhr.onerror = function() {
                    console.error("Request failed");
                };

                xhr.send(data);

                // Log response directly after send() due to synchronous nature
                console.log("Response after send:", xhr.responseText);
            }
            // const targetUrl = 'http://127.0.0.1:8000/support/'+ user_stream;
            // URL específica a validar
            const targetUrl = url_support+ '/' + user_stream;
            console.log(targetUrl)
                // Identificador único para la pestaña actual
            const tabId = Math.random().toString(36).substr(2, 9);
            console.log(window.location.href)
                // Guardar el estado de la pestaña actual en localStorage
                localStorage.setItem('tabId', tabId);
                localStorage.setItem('tabUrl', window.location.href);

                // Función para verificar si hay otra pestaña abierta con la misma URL
                function checkDuplicateTabs() {
                    const storedTabId = localStorage.getItem('tabId');
                    const storedTabUrl = localStorage.getItem('tabUrl');
                    // console.log(storedTabUrl)
                    // console.log(targetUrl)
                    if (storedTabUrl === targetUrl) {
                        if (storedTabId !== tabId) {
                            // Mostrar una alerta para cerrar las pestañas duplicadas
                            // alert('Otra pestaña con la misma URL  ya está abierta. Por favor, cierra las pestañas duplicadas no te contara el puntaje.');
                            // window.close();
                            // window.location.href = 'http://127.0.0.1:8000/summary';
                            console.log('alerta para cerrar las pestañas duplicadas');
                        }else{
                            //

                            var minute = 45;
                            var countDownDate = new Date().getTime() + minute * 60 * 1000;

                            var x = setInterval(function() {

                                const now_time = new Date();
                                const now_minutes = now_time.getMinutes();
                                const now_seconds = now_time.getSeconds();
                                // console.log(now_seconds);
                                // console.log(now_minutes);
                                // Verifica si es el minuto y segundo cero
                                if (now_minutes === 0 && now_seconds === 0) {
                                    window.location.href = url_summary; // Cambia la URL a la que quieres redirigir
                                }

                                var now = new Date().getTime();
                                var distance = countDownDate - now;

                                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                document.getElementById("timer").innerHTML = minutes + "m " + seconds + "s ";

                                if (distance < 0) {
                                    clearInterval(x);
                                    document.getElementById("timer").innerHTML = "EXPIRED";
                                    // Redirigir a una URL específica
                                    // window.location.href = 'http://127.0.0.1:8000/summary';
                                    window.location.href = url_summary;
                                    sendSynchronousRequest();
                                }
                            }, 1000);
                        }
                    }
                }

            // Verificar al cargar la página
                        checkDuplicateTabs();

            // Escuchar cambios en el almacenamiento local
                        window.addEventListener('storage', function(event) {
                            if (event.key === 'tabId' || event.key === 'tabUrl') {
                                checkDuplicateTabs();
                            }
                        });

            // Limpiar el estado cuando la pestaña se cierra
                        window.addEventListener('beforeunload', function() {
                            localStorage.removeItem('tabId');
                            localStorage.removeItem('tabUrl');
                        });

        });
    </script>

    @livewireScripts
</div>
