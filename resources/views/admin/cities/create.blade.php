@extends('admin.layouts.create')

@section('title')
   Create New City
@stop

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="/admin/cities">Cities</a>
    </li>
    <li class="breadcrumb-item active">Create</li>
@stop
@section('form')
    <div class="card bg-dark card-form">
        <div class="card-body text-white">
            <h4 class="text-white font-weight-bold text-center">Create City</h4>
            <hr>
            <div class="row mt-3">
                <div class="col-sm-9 mx-auto">
                    {!! Form::open(['method' => 'POST', 'action' => 'CityController@store']) !!}
                    <div class="form-group">
                        {!! Form::label('name', 'Name:') !!}
                        {!! Form::text('name', null, ['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('state', 'State:') !!}
                        {!! Form::text('state', null, ['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('full_state', 'Full State:') !!}
                        {!! Form::text('full_state', null, ['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <a href="{{route('cities.index')}}" class="btn btn-link text-light">Cancel</a>
                        {!! Form::submit('Submit', ['class'=>'btn btn-primary']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection