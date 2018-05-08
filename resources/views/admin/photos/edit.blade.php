@extends('admin.layouts.edit')

@section('title')
    Poster
@stop

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="/admin/photos">Posters</a>
    </li>
    <li class="breadcrumb-item active">Edit</li>
@stop

@section('cards')
    <div class="col">
        <div class="card text-white bg-success o-hidden mb-4">
            <div class="card-body">
                <div class="card-body-icon">
                    <i class="fa fa-fw fa-shopping-cart"></i>
                </div>
                <div class="mr-5">{{$photo->event->count()}} Ticket {{str_plural('Seller', $photo->event->count())}}!</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="#">
                <span class="float-left">View events</span>
                <span class="float-right">
                                            <i class="fa fa-angle-right"></i>
                                        </span>
            </a>
        </div>
    </div>
    <div class="col">
        <div class="card text-white bg-danger o-hidden mb-4">
            <div class="card-body">
                <div class="card-body-icon">
                    <i class="fa fa-fw fa-shopping-cart"></i>
                </div>
                <div class="mr-5">{{$photo->event->count()}} Ticket {{str_plural('Seller', $photo->event->count())}}!</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="#">
                <span class="float-left">View events</span>
                <span class="float-right">
                                            <i class="fa fa-angle-right"></i>
                                        </span>
            </a>
        </div>
    </div>
    <div class="col">
        <div class="card text-white bg-primary o-hidden mb-4">
            <div class="card-body">
                <div class="card-body-icon">
                    <i class="fa fa-fw fa-shopping-cart"></i>
                </div>
                <div class="mr-5">{{$photo->event->count()}} Ticket {{str_plural('Seller', $photo->event->count())}}!</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="#">
                <span class="float-left">View events</span>
                <span class="float-right">
                                            <i class="fa fa-angle-right"></i>
                                        </span>
            </a>
        </div>
    </div>
@stop

@section('form')
    <div class="card bg-dark card-form">
        <div class="card-body text-white">
            <h4 class="text-white font-weight-bold text-center">{{$photo->event->name}} / {{$photo->event->city->name}}</h4>
            <div class="mt-4">
                {!! Form::model($photo, ['method' => 'PATCH', 'action' => ['PhotoController@update', $photo->id], 'files' => true]) !!}
                <div class="form-group">
                    {!! Form::label('event_id', 'Event:') !!}
                    {!! Form::select('event_id', array(''=>'Select an Event')  + $events_cities , null,['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('folder_id', 'Folder:') !!}
                    {!! Form::select('folder_id', array(''=>'Select a Folder')  + $folders , null,['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('photo', 'Poster:') !!}
                    {!! Form::file('photo', ['class'=>'form-control-file']) !!}
                </div>
                <div class="form-group">
                    <a href="{{route('events.edit', $photo->event->id)}}" class="btn btn-link text-light">Cancel</a>
                    {!! Form::submit('Submit', ['class'=>'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop
