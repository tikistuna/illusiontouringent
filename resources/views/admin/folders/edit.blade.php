@extends('admin.layouts.edit')

@section('title')
    Folder
@stop

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="/admin/folders">Folders</a>
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
                <div class="mr-5">{{$folder->photos->count()}} Ticket {{str_plural('Seller', $folder->photos->count())}}!</div>
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
                <div class="mr-5">{{$folder->photos->count()}} Ticket {{str_plural('Seller', $folder->photos->count())}}!</div>
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
                <div class="mr-5">{{$folder->photos->count()}} Ticket {{str_plural('Seller', $folder->photos->count())}}!</div>
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
            <h4 class="text-white font-weight-bold text-center">{{$folder->name}}</h4>
            <div class="mt-4">
                {!! Form::model($folder, ['method' => 'PATCH', 'action' => ['FolderController@update', $folder->id]]) !!}
                <div class="form-group">
                    {!! Form::label('name', 'Name:') !!}
                    {!! Form::text('name', null, ['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    <a href="{{route('folders.index')}}" class="btn btn-link text-light">Cancel</a>
                    {!! Form::submit('Submit', ['class'=>'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop
