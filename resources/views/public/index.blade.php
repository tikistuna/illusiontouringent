@extends('layouts.public')

@section('head')
    <link rel="stylesheet" href="/css/public-index.css">
@stop

@section('messages')
    @if(session('message_email'))
        <div class="alert alert-primary alert-dismissible fade show" id="dismissible-email">
            <button data-dismiss="alert" class="close" type="button">
                <span>&times;</span>
            </button>

            <strong>{{session('message_email')}}</strong>
        </div>
    @endif
    @if(session('message_phone'))
        <div class="alert alert-primary alert-dismissible fade show" id="dismissible-phone">
            <button data-dismiss="alert" class="close" type="button">
                <span>&times;</span>
            </button>
            <strong>{{session('message_phone')}}</strong>
        </div>
    @endif
@stop

@section('main')
    @php $isMobile = true; @endphp
    <div id="featuring-video">
        <div class="iframe-wrapper">
            <iframe src="https://www.youtube.com/embed/T30ddyWa38M?rel=0&amp;showinfo=0" frameborder="0" allow="encrypted-media" allowfullscreen></iframe>
        </div>
    </div>
    <div id="suscribe-top">
        <div class="card bg-dark card-form">
            <div class="card-body">
                <h4 class="text-white">&#161;Regístrate y participa para ganar boletos&#33;</h4>
                <div class="mt-4">
                    {!! Form::open(['method' => 'POST', 'action' => 'SuscriberController@store']) !!}
                    <div class="form-group">
                        {!! Form::label('name', 'Nombre:', ['class'=>'text-white font-weight-bold']) !!}
                        {!! Form::text('name', null, ['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('email', 'E-mail:', ['class'=>'text-white font-weight-bold']) !!}
                        {!! Form::text('email', null, ['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('phone', 'Teléfono:', ['class'=>'text-white font-weight-bold']) !!}
                        {!! Form::text('phone', null, ['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('city', 'Ciudad:', ['class'=>'text-white font-weight-bold']) !!}
                        {!! Form::select('city', array('' => 'Seleccione una Ciudad') + $cities, null, ['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group mt-4">
                        {!! Form::submit('Registrar&#33;', ['class'=>'btn btn-primary btn-block']) !!}
                    </div>
                    <small class="text-muted">Tarifas de mensaje de texto pueden aplicar. Hasta 5 mensajes por mes, por ciudad. Para dejar de recibir mensajes click <a class="text-muted font-italic" href="{{route('suscribers.destroy')}}">aqu&iacute;</a></small>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <h1 class="h4 text-white m-0 pb-4 pb-md-5 pt-5 pt-lg-4" id="main-title">
        @if(isset($city))
            Pr&oacute;ximos eventos en {{$city->name}}...
        @else
            Pr&oacute;ximos eventos...
        @endif
    </h1>
@stop

@section('content')
    <?php
    $count = 1;
    setlocale(LC_TIME, 'es_ES.UTF-8');
    ?>
    @foreach($events as $event)
        <section class="event-section py-3" data-id="{{$count}}">
            <div class="event-section-overlay py-3">
                <div class="container">
                    <div class="pt-3">
                        <h1 class="text-center event-name event-name-light h2">{{$event->name}}</h1>
                    </div>
                    <div class="row">
                        <div class="col-9 mx-auto col-md-6 col-lg-5 pt-4">
                            <img src="{{$event->photo->path}}" alt="" class="img-fluid">
                        </div>
                        <div class="col-10 mx-auto col-md-6 ml-md-auto pt-5 text-white event-info">
                            <p class="event-header-info mb-2">
                                <i class="fa fa-calendar"></i>
                                {{ $event->getDateFormatted() }}
                            </p>
                            <p class="event-header-info mb-3">
                                <i class="fa fa-map-marker"></i>
                                {{$event->venue->name}} @ {{$event->city->name}}, {{$event->city->state}}.
                            </p>
                            <p class="event-description">
                                <?php
                                echo $event->description;
                                ?>
                            </p>
                            <div class="d-none d-md-block">
                                <div class="iframe-wrapper" id="{{$event->id}}">
                                    <iframe src="{{$event->venue->google_maps()}}" frameborder="0" style="border:0"></iframe>
                                </div>
                            </div>
                            <div class="mt-3 event-tickets">
                                <p>
                                    <i class="fa fa-usd"></i>
                                    {{$event->pricesAsString}}
                                </p>
                                <p class="mb-2" style="position:relative;left:-0.2em">
                                    <i class="fa fa-ticket"></i>
                                    Boletos:
                                </p>
                                <div class="d-flex flex-row">
                                    @foreach($event->ticket_sellers as $ticket_seller)
                                        <div class="mr-4">
                                            <p class="mb-1">
                                                {{$ticket_seller->name}}
                                            </p>
                                            <div class="event-link">
                                                @if($ticket_seller->pivot->website)
                                                    <p class="mt-0 {{($ticket_seller->address or $ticket_seller->phone or $ticket_seller->hours) ? 'mb-1' : ''}}">
                                                        <a href="{{$ticket_seller->pivot->website}}" class="text-light">Comprar en l&iacute;nea</a>
                                                    </p>
                                                @elseif($ticket_seller->website)
                                                    <p class="mt-0 {{($ticket_seller->address or $ticket_seller->phone or $ticket_seller->hours) ? 'mb-1' : ''}}">
                                                        <a href="http://{{$ticket_seller->website}}" class="text-light">Comprar en l&iacute;nea</a>
                                                    </p>
                                                @endif
                                                @if($ticket_seller->address)
                                                    <p class="mt-0 {{($ticket_seller->phone or $ticket_seller->hours) ? 'mb-1' : ''}}">
                                                        <button class="btn btn-link text-light iframe-toggler p-0" data-target="{{$event->id}}" data-mapsrc="{{$ticket_seller->google_maps($event->city)}}">Direcci&oacute;n
                                                        </button>
                                                    </p>
                                                @endif
                                                @if($ticket_seller->phone)
                                                    <p class="mt-0 {{($ticket_seller->hours) ? 'mb-1' : ''}}">
                                                        <button class="btn btn-link p-0 text-light" data-toggle="popover" data-placement="bottom" data-content="{{$ticket_seller->phone}}" data-original-title="Tel&eacute;fono">Tel&eacute;fono</button>
                                                    </p>
                                                @endif
                                                @if($ticket_seller->hours)
                                                    <p class="mt-0">
                                                        <button class="btn btn-link p-0 text-light" data-toggle="popover" data-placement="bottom" data-content="{{$ticket_seller->hours}}" data-original-title="Horarios">Horarios</button>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php $count++; ?>
    @endforeach

    <section id="loading" class="text-center text-white py-5 d-none">
        <i class="fa fa-spinner fa-pulse text-white" style="font-size:5em"></i>
    </section>
    @if($isMobile)
        <section class="row mb-4 container">
            <div class="col-1 mx-auto">
                <button class="btn btn-outline-light mx-auto" id="lazy-loader">Ver m&aacute;s</button>
            </div>
        </section>
    @endif
    <div class="container py-5" id="suscribe-bottom">
        <div>
            <div class="card bg-dark card-form">
                <div class="card-body">
                    <h4 class="text-white">&#161;Regístrate y participa para ganar boletos&#33;</h4>
                    <div class="mt-4">
                        {!! Form::open(['method' => 'POST', 'action' => 'SuscriberController@store']) !!}
                        <div class="form-group">
                            {!! Form::label('name', 'Nombre:', ['class'=>'text-white font-weight-bold']) !!}
                            {!! Form::text('name', null, ['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('email', 'E-mail:', ['class'=>'text-white font-weight-bold']) !!}
                            {!! Form::text('email', null, ['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('phone', 'Teléfono:', ['class'=>'text-white font-weight-bold']) !!}
                            {!! Form::text('phone', null, ['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('city', 'Ciudad:', ['class'=>'text-white font-weight-bold']) !!}
                            {!! Form::select('city', array('' => 'Seleccione una Ciudad') + $cities, null, ['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group mt-4">
                            {!! Form::submit('Registrar&#33;', ['class'=>'btn btn-primary btn-block']) !!}
                        </div>
                        <small class="text-muted">Tarifas de mensaje de texto pueden aplicar. Hasta 5 mensajes por mes, por ciudad. Para dejar de recibir mensajes click <a class="text-muted font-italic" href="{{route('suscribers.destroy')}}">aqu&iacute;</a></small>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('scripts')

    <script>
        var loading = $("#loading");

        function changeMap(){

            var target = $("#" + $(this)[0].dataset.target);
            var map = target.children().first();
            map.attr('src', $(this)[0].dataset.mapsrc);
            target.parent().removeClass("d-none");


        }

        var busy = false;

        function lazyLoader(){
            @if(!$isMobile)
                if($(window).scrollTop() + $(window).height() > $(document).height() - 500 && !(busy) ) {


                @endif

                        loading.removeClass('d-none');
                        busy = true;
                        var offset = $(".event-section").last()[0].dataset.id;

                        var request = $.ajax({
                            async: true,
                            type: "GET",
                            url: "/load/" + offset <?php echo(isset($city) ? "+'/{$city->id}'" : '') ?>,
                            dataType: 'json',
                            success: function (events) {
                                if (events.status === 'success') {
                                    $(events.html).insertBefore("#loading");
                                    busy = false;
                                    $('[data-toggle="popover"]').popover();
                                } else {
                                    @if($isMobile)
                                        $('#lazy-loader').addClass("d-none");
                                    @endif
                                }

                                loading.addClass("d-none");
                            }
                        });

                        @if(!$isMobile)
                            }
                        @endif

        }

        $(document).ready(function(){
            $('[data-toggle="popover"]').popover();
            $(document).on("click", ".iframe-toggler",changeMap);
            @if($isMobile)
                $('#lazy-loader').click(lazyLoader);
            @else
                $(window).scroll(lazyLoader);
            @endif

        });

    </script>


@stop
