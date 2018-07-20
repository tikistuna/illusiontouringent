@extends('layouts.public')
@section('head')
    <style>
        .list-group-item{
            background: transparent !important;
            border: none !important;
            padding: 0.25rem 1.75rem 0.25rem 0 !important;
        }

        #city-menu{
            min-height: calc(100vh - 200px);
        }
    </style>
@stop
@section('main')
    <div class="container text-white">
        @if($past)
            <h1>Eventos Pasados en tu ciudad...</h1>
        @else
            <h1>Siguientes eventos en tu ciudad...</h1>
        @endif
            <div class="d-flex flex-row flex-wrap justify-content-around mt-5" id="city-menu">
                @foreach($states as $state)
                    <div class="mx-md-5 mr-3 my-4">
                        <h2 class="h1">{{$state[0]['full_state']}}</h2>
                        <ul class="list-group">
                            @foreach($state as $city)
                                <li class="list-group-item">
                                    @if($past)
                                        <a class="text-white" href="{{route('public.past', ['past' => $city['name']])}}">{{$city['name']}}</a>
                                    @else
                                        <a class="text-white" href="{{route('public.cities', ['past' => $city['name']])}}">{{$city['name']}}</a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
    </div>
@stop