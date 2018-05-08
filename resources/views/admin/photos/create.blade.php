@extends('admin.layouts.create')

@section('title')
   Create New Poster
@stop

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="/admin/photos">Posters</a>
    </li>
    <li class="breadcrumb-item active">Create</li>
@stop
@section('form')
    <div class="card bg-dark card-form">
        <div class="card-body text-white">
            <h4 class="text-white font-weight-bold text-center">Create Poster</h4>
            <hr>
            <div class="row mt-3">
                <div class="col-sm-9 mx-auto">
                    {!! Form::open(['method' => 'POST', 'action' => 'PhotoController@store', 'files' => true]) !!}
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
                        <a href="{{route('events.index')}}" class="btn btn-link text-light">Cancel</a>
                        {!! Form::submit('Submit', ['class'=>'btn btn-primary']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection