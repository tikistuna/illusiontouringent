<?php
$count = $offset + 1;
setlocale(LC_TIME, 'es_ES.UTF-8');
?>
@foreach($events as $event)
    <section class="event-section" data-id="{{$count}}">
        <div class="event-section-overlay py-3">
            <div class="container">
                <div class="pt-3">
                    <h1 class="text-center event-name event-name-light h2">{{$event->name}}</h1>
                </div>
                <div class="row">
                    <div class="col-9 mx-auto col-md-6 col-lg-5 pt-4">
                        <img src="{{$event->photo->path}}" alt="" class="img-fluid">
                    </div>
                    <div class="col-10 mx-auto col-md-6 ml-md-auto pt-5 text-white">
                        <p class="event-header-info mb-2">
                            <i class="fa fa-calendar"></i>
                            {{$event->getDateFormatted()}}
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
                            <p class="mb-2" style="position:relative;left:-0.2em">
                                <i class="fa fa-ticket"></i>
                                Boletos:
                            </p>
                            <p>
                                <i class="fa fa-usd"></i>
                                {{$event->getPrices()}}
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
