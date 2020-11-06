<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('style')
</head>
<body>
    <div id="app">
        @include('ui.nav')
        @include('ui.flash')
        <main class="mt-4">
            @yield('content')
        </main>
        <footer class="footer" data-background-color="black">
            <div class="container d-flex align-items-center justify-content-between">
                <nav>
                    <ul>
                        <li>
                            <a class="h3" target="_blank" href="https://github.com/dendihandian"><i class="fab fa-github"></i></a>
                        </li>
                        <li>
                            <a class="h3" target="_blank" href="https://linkedin.com/in/dendihandian"><i class="fab fa-linkedin"></i></a>
                        </li>
                    </ul>
                </nav>
                <div class="copyright float-right">
                Dendi Handian © {{ Date('Y') }} 
                </div>
            </div>
        </footer>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.20/lodash.min.js" integrity="sha512-90vH1Z83AJY9DmlWa8WkjkV79yfS2n2Oxhsi2dZbIv0nC4E6m5AbH8Nh156kkM7JePmqD6tcZsfad1ueoaovww==" crossorigin="anonymous"></script>
    @yield('script')
    <script>
        var alert = document.querySelector('.alert');
        if (alert) {
            setTimeout(function(){
                alert.style.display = 'none';
            }, 5000);
        }
    </script>
</body>
</html>
