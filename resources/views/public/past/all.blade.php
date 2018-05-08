@extends('layouts.public')

@section('head')
    <link rel="stylesheet" href="/css/public-past-events.css">
@stop

@section('main-container')
    container
@stop

@section('main')
    <h1 class="pl-2 pb-5 text-white h2" id="main-title">
        Eventos Pasados...
    </h1>

    <ul class="flex-container pl-0">
        @for($i = 0; $i < $count; $i++)
            <li class="flex-item px-2 pb-5 pt-3">
                <div class="section-past-event px-2">
                    <h1 class="text-center event-name event-name-light">{{$events[$keys[$i]][0]->name}}</h1>
                    <div class="mx-auto pt-4">
                        <div id="{{$events[$keys[$i]][0]->name}}" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                @php
                                    $k = $events[$keys[$i]]->count();
                                @endphp
                                @for($j=0; $j<$k; $j++)
                                    <li class="active" data-target="#{{$events[$keys[$i]][0]->name}}" data-slide-to="0"></li>
                                @endfor
                            </ol>
                            <div class="carousel-inner" role="listbox">

                                @for($j=0; $j<$k; $j++)
                                    <div class="carousel-item {{$j === 0 ? 'active' : ''}}">
                                        <div class="text-white text-center event-info">
                                            <p class="mb-1">
                                                <i class="fa fa-calendar"></i>
                                                {{$events[$keys[$i]][$j]->getDateFormatted()}}
                                            </p>
                                            <p>
                                                <i class="fa fa-map-marker"></i>
                                                {{$events[$keys[$i]][$j]->venue->name}} @ {{$events[$keys[$i]][$j]->city->name}}, {{$events[$keys[$i]][$j]->city->state}}.
                                            </p>
                                        </div>
                                        <img src="{{$events[$keys[$i]][$j]->photo->path}}" alt="" class="d-block img-fluid">
                                    </div>
                                @endfor
                                <a href="#{{$events[$keys[$i]][0]->name}}" class="carousel-control-prev" data-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </a>
                                <a href="#{{$events[$keys[$i]][0]->name}}" class="carousel-control-next" data-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        @endfor
    </ul>

@stop