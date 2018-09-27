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

        @media screen and (max-width: 30em){
            h1{
                font-size: 2.2rem !important;
            }

            h2.h1{
                font-size: 1.8rem !important;
            }
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
            <div class="row mt-5" id="city-menu">
                @foreach($states as $state)
                    <div class="col-6 px-4 mx-md-5 my-4">
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