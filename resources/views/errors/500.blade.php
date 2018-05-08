<!DOCTYPE html>
<html lang="es-MX" class="fa-events-icons-ready">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>L.J. Productions</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/public-layout.css">
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans:700" rel="stylesheet">
    <link rel="icon" href="/assets/logos/logo.png" type="image/png">
</head>
<body>
<div class="background-img">
    <img src="/assets/backgrounds/concert.jpg" alt="">
</div>
@include('public.navigation')
<div class="dark-overlay" style="min-height: 700px">
    <div class="container text-white" style="height: inherit">
        <div class="d-flex flex-row align-items-center" id="errors">
            <div class="mx-auto">
                <h1 class="display-4">
                    <i class="fas fa-thumbs-down"></i> Oops, parece que algo sali&oacute; mal
                </h1>

            </div>
        </div>
    </div>
    <footer id="main-footer" class="bg-dark fixed-bottom">
        <div class="container">
            <div class="row mt-1">
                <div class="col text-center text-white">
                    <div class="py-2">
                        <img class="img-fluid" src="/assets/logos/logo.png" width="102" height="73">
                        <a class="h5" href="{{route('public.index')}}" style="position: relative; top:0.3em;">L.J. Productions</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
<script src="/js/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        var height = $(window).height();
        $('.dark-overlay').css('height', height);
        var innerHeight = height - $('#main-footer').height() - $('.navbar').height();
        $('#errors').css('height', innerHeight).css('padding-top', $('.navbar').height());
    });
</script>
</body>
</html>