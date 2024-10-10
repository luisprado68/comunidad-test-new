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
                                <div class="card-body banner-twich" >
                                    <div class="row mb-2">
                                        <div class="col-lg-2 col-sm-8 mb-4">
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
                                    <div id="{{'contadorPunto' . $user_stream->id}}">
                                        <h5 class="card-title text-light">Cantidad de tiempo</h5>
                                        {{--                                    <h6 class="card-subtitle mb-2  text-light">00:00</h6>--}}
                                        <h6 class="card-subtitle mb-2  text-light" id="{{'timer_' . $user_stream->id}}">00:00</h6>
                                    </div>
                                    <div id="{{'inicio' . $user_stream->id}}">
                                        <h5 class="card-title text-light">Tiempo de espera para iniciar</h5>
                                        <h6 class="card-subtitle mb-2  text-light"><span id="{{'contador' . $user_stream->id}}">15:00</span></h6>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                </div>
            </div>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            var minites_before = @json($minites_before);
            let user_stream = @json($user_stream->id);

            // Tiempo inicial (15 minutos en segundos)
            let tiempoRestante = (15 - minites_before) * 60 ;
            // Función para formatear el tiempo en formato MM:SS
            function formatearTiempo(segundos_antes) {
                const minutos_antes = Math.floor(segundos_antes / 60);
                const segundosRestantes = segundos_antes % 60;
                return `${minutos_antes.toString().padStart(2, '0')}:${segundosRestantes.toString().padStart(2, '0')}`;
            }
            // Función que actualiza el contador cada segundo
            function actualizarContador() {
                const contadorElemento = document.getElementById('contador'+ user_stream);
                const contadorPunto = document.getElementById('contadorPunto'+ user_stream);
                const inicio = document.getElementById('inicio'+ user_stream);
                inicio.classList.add("show_div");
                contadorPunto.classList.add("hide_div");

                if (tiempoRestante >= 0) {
                    contadorElemento.textContent = formatearTiempo(tiempoRestante);
                    tiempoRestante--;
                } else {
                    clearInterval(intervalo); // Detener el intervalo cuando el tiempo llegue a 0
                    contadorElemento.textContent = "00";
                    contadorPunto.classList.remove("hide_div");
                    contadorPunto.classList.add("show_div");
                    inicio.classList.remove("show_div");
                    inicio.classList.add("hide_div");
                    checkDuplicateTabs();
                }
            }
            const intervalo = setInterval(actualizarContador, 1000);

            function getCsrfToken() {
                return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            }

            var user_model_id = @json($user_model_id);
            var url_summary =  @json($url_summary);
            var url_support =  @json($url_support);
            var url_plus_point = @json($url_plus_point);
            var minute_initial = @json($minimum_time);
            function sendSynchronousRequest() {

                var xhr = new XMLHttpRequest();
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
                        window.close();
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
            // URL específica a validar
            const targetUrl = url_support+ '/' + user_stream;
            console.log(targetUrl)
                // Identificador único para la pestaña actual
            const tabId = Math.random().toString(36).substr(2, 9);
            // antes console.log(window.location.href)
                // Guardar el estado de la pestaña actual en localStorage
                localStorage.setItem('tabId', tabId);
                localStorage.setItem('tabUrl', targetUrl);//window.location.href

                // Función para verificar si hay otra pestaña abierta con la misma URL
                function checkDuplicateTabs() {
                            // console.log('tiempoRestante');
                            // console.log(tiempoRestante);
                            if (tiempoRestante < 0) {
                                var minute = parseInt(localStorage.getItem('minutes_'+user_stream)) || minute_initial;
                                if(minute < minute_initial){
                                    //fix para evitar que baje al recargar la pagina
                                    minute = minute + 1;
                                }
                                var countDownDate = new Date().getTime() + minute * 60 * 1000;

                                var x = setInterval(function() {
                                    const now_time = new Date();
                                    const now_minutes = now_time.getMinutes();
                                    const now_seconds = now_time.getSeconds();
                                    // Verifica si es el minuto y segundo cero
                                    if (now_minutes === 0 && now_seconds === 0) {
                                        window.location.href = url_summary; // Cambia la URL a la que quieres redirigir
                                    }
                                    var now = new Date().getTime();
                                    var distance = countDownDate - now;
                                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                    localStorage.setItem('minutes_'+user_stream, minutes.toString());
                                    document.getElementById("timer_"+user_stream).innerHTML = minutes + "m " + seconds + "s ";

                                    if (distance < 0) {
                                        clearInterval(x);

                                        document.getElementById("timer_"+user_stream).innerHTML = "EXPIRED";
                                        localStorage.removeItem('minutes_'+user_stream);
                                        // Redirigir a una URL específica
                                        // window.location.href = url_summary;
                                        sendSynchronousRequest();
                                    }
                                }, 1000);
                            }
                }
        });
    </script>
    @livewireScripts
</div>
