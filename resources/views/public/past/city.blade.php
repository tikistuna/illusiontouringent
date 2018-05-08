@extends('layouts.public')

@section('head')
    <link rel="stylesheet" href="/css/public-past-events.css">

    <style>
        .section-past-event img{
            width: inherit;
        }
    </style>
@stop

@section('main-container')
    container
@stop

@section('main')
    <h1 class="h2 pl-2 pb-5 text-white" id="main-title">
        Eventos Pasados en {{$city}}...
    </h1>
    <ul class="flex-container pl-0">
        @for($i=0; $i<$count; $i++)
            <li>
                <div class="py-4 px-2 mx-auto section-past-event">
                    <h1 class="text-center event-name event-name-light">
                        {{$events[$keys[$i]][0]->name}}
                    </h1>
                    @php
                        $k = $events[$keys[$i]]->count();
                    @endphp
                    @for($j=0; $j<$k; $j++)
                        <div class="text-center pt-4 text-white event-info">
                            <p class="mb-1">
                                <i class="fa fa-calendar"></i>
                                {{$events[$keys[$i]][$j]->getDateFormatted()}}
                            </p>
                            <p>
                                <i class="fa fa-map-marker"></i>
                                {{$events[$keys[$i]][$j]->venue->name}}
                            </p>
                            <div class="col-12 mx-auto">
                                <img src="{{$events[$keys[$i]][$j]->photo->path}}" alt="" class="img-fluid">
                            </div>

                        </div>
                    @endfor
                </div>
            </li>
        @endfor
    </ul>
@stop