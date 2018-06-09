@extends('admin.layouts.edit')

@section('title')
    City
@stop

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="/admin/cities">Cities</a>
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
                <div class="mr-5">{{$city->events->count()}} {{str_plural('Event', $city->events->count())}}!</div>
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
                <div class="mr-5">{{$city->events->count()}} {{str_plural('Event', $city->events->count())}}!</div>
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
                <div class="mr-5">{{$city->events->count()}} {{str_plural('Event', $city->events->count())}}!</div>
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
    <div class="card bg-dark text-white card-form">
        <div class="card-header text-center">
            <h4 class="font-weight-bold">{{$city->name}}</h4>
        </div>
        <div class="card-body">
            <div class="mt-4">
                {!! Form::model($city, ['method' => 'PATCH', 'action' => ['CityController@update', $city->id]]) !!}
                <div class="form-group">
                    {!! Form::label('name', 'Name:') !!}
                    {!! Form::text('name', null, ['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('state', 'State:') !!}
                    {!! Form::text('state', null, ['class'=>'form-control']) !!}
                </div>
                <div class="form-group mt-5">
                    <a href="{{URL::previous()}}" class="btn btn-link text-light">Cancel</a>
                    {!! Form::submit('Submit', ['class'=>'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop


