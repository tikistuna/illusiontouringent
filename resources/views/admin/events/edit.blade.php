@extends('admin.layouts.edit')

@section('title')
    Event
@stop

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="/admin/events">Events</a>
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
                <div class="mr-5">{{$event->ticket_sellers->count()}} Ticket {{str_plural('Seller', $event->ticket_sellers->count())}}!</div>
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
                <div class="mr-5">{{$event->ticket_sellers->count()}} Ticket {{str_plural('Seller', $event->ticket_sellers->count())}}!</div>
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
                <div class="mr-5">{{$event->ticket_sellers->count()}} Ticket {{str_plural('Seller', $event->ticket_sellers->count())}}!</div>
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
    <div class="img-flex-wrapper">
        <a href="{{route('photos.edit', $event->photo->id)}}"><img src="{{$event->photo->path}}" alt="" class="img-responsive img-shadow-box"></a>
    </div>
    <div class="card bg-dark card-form" style="max-width: 50%;">
        <div class="card-body text-white">
            <h4 class="text-white font-weight-bold text-center">{{$event->name}} / {{$event->city->name}}</h4>
            <div class="mt-4">
                {!! Form::model($event, ['method' => 'PATCH', 'action' => ['EventController@update', $event->id]]) !!}
                <div class="form-group">
                    {!! Form::label('name', 'Name:', ['class' => 'font-weight-bold']) !!}
                    {!! Form::text('name', null, ['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('city_id', 'City:', ['class' => 'font-weight-bold']) !!}
                    {!! Form::select('city_id', array(''=>'Select a City')  + $cities , null,['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('venue_id', 'Venue:', ['class' => 'font-weight-bold']) !!}
                    {!! Form::select('venue_id', array(''=>'Select a Venue')  + $venues , null,['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('date', 'Date:', ['class' => 'font-weight-bold']) !!}
                    {!! Form::date('date', $date, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('description', 'Description:', ['class' => 'font-weight-bold']) !!}
                    {!! Form::textarea('description', null, ['class'=>'form-control', 'rows' => 3]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('reminder_description', 'Description for Reminder:', ['class' => 'font-weight-bold']) !!}
                    {!! Form::textarea('reminder_description', null, ['class'=>'form-control', 'rows' => 3]) !!}
                </div>
                <div class="form-group">
                    <a href="{{route('events.index')}}" class="btn btn-link text-light">Cancel</a>
                    {!! Form::submit('Edit Event', ['class'=>'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop

