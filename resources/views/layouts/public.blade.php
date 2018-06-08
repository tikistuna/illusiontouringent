<!DOCTYPE html>
<html lang="es-MX" class="fa-events-icons-ready">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie-edge">
        <title>Illusion Touring Entertainment</title>
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/public-layout.css">
        <script src="https://use.fontawesome.com/04597954ff.js"></script>
        <link href="https://fonts.googleapis.com/css?family=Noto+Sans:400,700" rel="stylesheet">
        <link rel="icon" href="/assets/logos/logo.png" type="image/png">
        @yield('head')
    </head>
    <body>

        @include('public.navigation')

        @yield('messages')

        <div class="background-img">
            <img src="/assets/backgrounds/concert.jpg" alt="">
        </div>
        <div class="dark-overlay">
            <main id="main-section">
                <div class="main-inner @yield('main-container')">
                    @yield('main')
                </div>
            </main>


            @yield('content')

            <footer id="main-footer" class="bg-dark @yield('fix_footer')">
                <div class="container">
                    <div class="row mt-1">
                        <div class="col text-center text-white">
                            <div class="py-2">
                                <img class="img-fluid" src="/assets/logos/logo.png" width="102" height="73">
                                <a class="h5" href="{{route('public.index')}}" style="position: relative; top:0.3em;">Illusion Touring Entertainment</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>




        <script src="/js/jquery.min.js"></script>
        <script src="/js/tether.min.js"></script>
        <script src="/js/bootstrap.bundle.min.js"></script>
        <script>
            $(window).resize(function(){
                if($(window).width() < 768){
                    $(".drop_menu").addClass('hamburger_menu');
                }else if($(window).width() > 768){
                    $(".drop_menu").removeClass('hamburger_menu');
                }
            });
        </script>
        @yield('scripts')
    </body>
</html>