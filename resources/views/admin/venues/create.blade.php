@extends('admin.layouts.create')

@section('title')
    Create New Venue
@stop

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="/admin/venues">Venues</a>
    </li>
    <li class="breadcrumb-item active">Create</li>
@stop
@section('form')
    <div class="card bg-dark card-form">
        <div class="card-body text-white">
            <h4 class="text-white font-weight-bold text-center">Create Venue</h4>
            <hr>
            <div class="row mt-3">
                <div class="col-sm-9 mx-auto">
                    {!! Form::open(['method' => 'POST', 'action' => 'VenueController@store']) !!}
                    <div class="form-group">
                        {!! Form::label('name', 'Name:') !!}
                        {!! Form::text('name', null, ['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('city_id', 'City:') !!}
                        {!! Form::select('city_id', array(''=>'Select a City')  + $cities , null,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <a href="{{route('venues.index')}}" class="btn btn-link text-light">Cancel</a>
                        {!! Form::submit('Submit', ['class'=>'btn btn-primary']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection