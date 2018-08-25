@extends('admin.layouts.admin')

@section('head')
<style>
    .bg-navy{
        background-color: #000099!important;
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
                    <a class="float-right text-dark" href="/admin/events/create">New Event</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="row p-2 pb-3" id="table-header-info">
                            <div class="input-group col-4" >
                                <input id="search-query" type="text" class="form-control" placeholder="Search for an event..." v-model="query">
                                <span class="input-group-btn">
                            <button id="search-submit" class="btn btn-dark" type="button" @click="queryEvents(query)">Search</button>
                        </span>
                            </div>
                        </div>
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead class="bg-danger">
                                <tr>
                                    <th>#</th>
                                    <th></th>
                                    <th>Artista</th>
                                    <th>Ciudad</th>
                                    <th>Día</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Teatro</th>
                                    <th>Precios</th>
                                    <th>Direcci&oacute;n</th>
                                    <th>Taquilla</th>
                                    <th>Boletera</th>
                                    <th>Tel&eacute;fono</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($events as $event)
                                    <tr>
                                        <td>{{$loop->index}}</td>
                                        @if($event->illusion)
                                            <td class="bg-navy">Illusion</td>
                                        @else
                                            <td class="bg-danger">L.J.</td>
                                        @endif
                                        <td class="bg-warning">{{$event->name}}</td>
                                        <td class="bg-red">{{$event->city->name}}</td>
                                        <td class="bg-light">{{$event->firstLetterOfDay()}}</td>
                                        <td class="bg-success px-2">{{$event->date->formatLocalized('%b-%d')}}</td>
                                        <td class="bg-light">{{$event->date->formatLocalized('%I:%M')}}</td>
                                        <td>{{$event->venue->name}}</td>
                                        <td>{{$event->pricesAsString}}</td>
                                        <td>Pr&oacute;ximamente</td>
                                        <td>Pr&oacute;ximamente</td>
                                        @if($event->ticket_sellers->count() > 0)
                                            <td>{{implode(', ', $event->ticket_sellers->pluck('name')->toArray())}}</td>
                                            @if($event->ticket_sellers->first()->phone)
                                                <td>{{$event->ticket_sellers->first()->phone}}</td>
                                            @else
                                                <td></td>
                                            @endif
                                        @else
                                            <td></td>
                                            <td></td>
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
                                    <th>Teatro</th>
                                    <th>Precios</th>
                                    <th>Direcci&oacute;n</th>
                                    <th>Taquilla</th>
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
