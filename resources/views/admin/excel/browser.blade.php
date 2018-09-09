@extends('admin.layouts.admin')

@section('head')
<style>
    .bg-navy{
        background-color: #0066ff!important;
    }

    .bg-red{
        background-color: #ec1313!important;
    }

    .bg-green{
        background-color: #2eb82e!important;
    }

    .bg-yellow{
        background-color: #ffde00!important;
    }

    .bg-medium-light{
        background-color: #d9d9d9!important;
    }

    .bg-light-blue{
        background-color: #b3d9ff!important;
    }

    .bg-light-green{
        background-color: #bbff99!important;
    }
    .bg-light-orange{
        background-color: #ffd699!important;
    }

    table * {
        font-size: 0.65rem;
    }
</style>
@stop

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Events</li>
            </ol>
            <div class="card mb-3" id="events-table">
                <div class="card-header">
                    <i class="fa fa-table"></i> Events
                    <div class="float-right">
                        {!! Form::open(['method' => 'POST', 'action' => 'AdminController@excel']) !!}
                        <div class="form-group">{!! Form::submit('Download', ['class'=>'btn btn-link']) !!}</div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead class="bg-red text-light">
                                <tr>
                                    <th>#</th>
                                    <th></th>
                                    <th>Artista</th>
                                    <th>Ciudad</th>
                                    <th>Día</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Precios</th>
                                    <th>Teatro</th>
                                    <th>Direcci&oacute;n</th>
                                    <th>Taquilla</th>
                                    <th>Tel&eacute;fono</th>
                                    <th>Boletera</th>
                                    <th>Tel&eacute;fono</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($events as $event)
                                    <tr>
                                        <td>{{$loop->index}}</td>
                                        @if($event->illusion)
                                            <td class="bg-navy text-light">Illusion</td>
                                        @else
                                            <td class="bg-red text-white">L.J.</td>
                                        @endif
                                        <td class="bg-yellow">{{$event->name}}</td>
                                        <td class="bg-red text-white">{{$event->city->name}}</td>
                                        <td class="bg-medium-light">{{$event->firstLetterOfDay()}}</td>
                                        <td class="bg-green text-light px-2">{{$event->date->formatLocalized('%b-%d')}}</td>
                                        <td class="bg-medium-light">{{$event->date->formatLocalized('%I:%M')}}</td>
                                        <td class="bg-light-blue">{{$event->pricesAsString}}</td>
                                        <td class="bg-medium-light">{{$event->venue->name}}</td>
                                        <td class="bg-light-green">{{$event->venue->address}}</td>
                                        <td class="bg-light-green">{{$event->venue->phone}}</td>
                                        <td class="bg-light-green">{{$event->venue->hours}}</td>
                                        @if($event->ticket_sellers->count() > 0)
                                            <td class="bg-medium-light">{{implode(', ', $event->ticket_sellers->pluck('name')->toArray())}}</td>
                                                <td class="bg-light-orange">
                                                    @foreach($event->ticket_sellers as $ticket_seller)
                                                        {{$ticket_seller->phone}},
                                                    @endforeach
                                                </td>
                                        @else
                                            <td class="bg-medium-light"></td>
                                            <td class="bg-light-orange"></td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th></th>
                                    <th>Artista</th>
                                    <th>Ciudad</th>
                                    <th>Día</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Precios</th>
                                    <th>Teatro</th>
                                    <th>Direcci&oacute;n</th>
                                    <th>Taquilla</th>
                                    <th>Tel&eacute;fono</th>
                                    <th>Boletera</th>
                                    <th>Tel&eacute;fono</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function(){
            $('body').addClass('sidenav-toggled');
        });
    </script>
@stop
