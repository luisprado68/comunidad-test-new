<div class="fondo_general">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- reempalzo cdn icon y min css --}}
    <link href="{{ asset('/css/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <link rel="icon" href="{{ asset('/img/logo_co.ico') }}">
    <link href="{{ asset('/css/mobiscroll.javascript.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/custom.css') }}" rel="stylesheet">
    <x-banner />

    <div class=" bg-gray-100">

        {{-- @livewire('navigation-menu') --}}
        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        @if (session()->has('user'))

            @if (Illuminate\Support\Facades\Route::current()->uri == 'summary' || Illuminate\Support\Facades\Route::current()->uri == 'summary/{id}'  )
                @livewire('layouts.nav',['class_nav' => session('user')['class_nav'],'user' => session('user')])
            @else
                @include('layouts.nav',['class_nav' => session('user')['class_nav']])
            @endif
        @else
            @include('layouts.nav',['class_nav' => 'banner-dark'])
        @endif

        <main >
            <div class="container" style="margin-bottom: 200px">
                <div class="row">

                    <div class="iframe-container">
                        <iframe
                            src="https://player.twitch.tv/?channel=aquino&parent=www.comunidadnc.com"
                            allowfullscreen>
                        </iframe>
                    </div>
                    <div class="iframe-container">
                        <iframe id="twitch-chat-embed"
                            src="https://www.twitch.tv/embed/aquino/chat?parent=www.comunidadnc.com"
                            allowfullscreen>
                        </iframe>
                    </div>
{{--                    <div class="col" style="margin-top: 50px !important;">--}}
{{--                        <iframe id="twitch-chat-embed"--}}
{{--                                src="https://www.twitch.tv/embed/aquino/chat?parent=www.comunidadnc.com"--}}
{{--                                height="500"--}}
{{--                                width="350">--}}
{{--                        </iframe>--}}
{{--                    </div>--}}
                </div>
            </div>
        </main>
        @include('layouts.footer')
    </div>






    @stack('modals')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script> --}}
    <script src="https://kit.fontawesome.com/252b802fc2.js" crossorigin="anonymous"></script>
    <script>
        window.INIT_MIN_MINUTE_CHATTER = `{{ env('INIT_MIN_MINUTE_CHATTER') }}`;
        window.INIT_MAX_MINUTE_CHATTER = `{{ env('INIT_MAX_MINUTE_CHATTER') }}`;
        window.SECOND_MIN_MINUTE_CHATTER = `{{ env('SECOND_MIN_MINUTE_CHATTER') }}`;
        window.SECOND_MAX_MINUTE_CHATTER = `{{ env('SECOND_MAX_MINUTE_CHATTER') }}`;

    </script>
    {{-- @if (env('APP_ENV') == 'local') --}}

    <script src="{{ asset('/js/mobiscroll.javascript.min.js') }}"></script>
    <script src="{{ asset('/js/custom.js') }}"></script>
    {{-- agrego por cdn bundel js remplazo --}}
    <script src="{{ asset('/js/bootstrap.bundle.min.js') }}"></script>
    @stack('schedule')
    @stack('chatters')
    @stack('copyText')
    @livewireScripts
</div>
