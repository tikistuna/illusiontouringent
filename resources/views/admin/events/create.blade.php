@extends('admin.layouts.create')

@section('title')
   Create New Event
@stop

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="/admin/events">Events</a>
    </li>
    <li class="breadcrumb-item active">Create</li>
@stop
@section('form')
    <div class="card bg-dark card-form">
        <div class="card-body text-white">
            <h4 class="text-white font-weight-bold text-center">Create Event</h4>
            <hr>
            <div class="row mt-3">
                <div class="col-sm-9 mx-auto">
                    {!! Form::open(['method' => 'POST', 'action' => 'EventController@store']) !!}
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
                        {!! Form::date('date', \Carbon\Carbon::now(), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('hour', 'Hour:', ['class' => 'font-weight-bold']) !!}
                        {!! Form::select('hour', array(''=>'Select an hour', 6 => '6:00PM' ,7 => '7:00PM', 8 => '8:00PM', 9 => '9:00PM' ), null,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('minute', 'Minute:', ['class' => 'font-weight-bold']) !!}
                        {!! Form::select('minute', array(''=>'Select minute', 0 => '00', 15 => '15', 30 => '30', 45 => '45'), null,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('prices', 'Prices:', ['class' => 'font-weight-bold']) !!}
                        {!! Form::text('prices', null, ['class'=>'form-control']) !!}
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
                        {!! Form::submit('Create Event', ['class'=>'btn btn-primary']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection