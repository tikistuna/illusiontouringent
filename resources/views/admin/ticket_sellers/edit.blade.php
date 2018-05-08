
@extends('admin.layouts.edit')

@section('title')
    Ticket Seller
@stop

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="/admin/ticket_sellers">Ticket Sellers</a>
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
                <div class="mr-5">{{$ticket_seller->events->count()}} Ticket {{str_plural('Seller', $ticket_seller->events->count())}}!</div>
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
                <div class="mr-5">{{$ticket_seller->events->count()}} Ticket {{str_plural('Seller', $ticket_seller->events->count())}}!</div>
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
                <div class="mr-5">{{$ticket_seller->events->count()}} Ticket {{str_plural('Seller', $ticket_seller->events->count())}}!</div>
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
            <h4 class="text-white font-weight-bold text-center">{{$ticket_seller->name}}</h4>
            <div class="mt-4">
                {!! Form::model($ticket_seller, ['method' => 'PATCH', 'action' => ['TicketSellerController@update', $ticket_seller->id]]) !!}
                <div class="form-group">
                    {!! Form::label('name', 'Name:') !!}
                    {!! Form::text('name', null, ['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('phone', 'Phone:') !!}
                    {!! Form::text('phone', null, ['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('address', 'Address:') !!}
                    {!! Form::text('address', null, ['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('website', 'Website:') !!}
                    {!! Form::text('website', null, ['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('hours', 'Hours:') !!}
                    {!! Form::text('hours', null, ['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    <a href="{{route('ticket_sellers.index')}}" class="btn btn-link text-light">Cancel</a>
                    {!! Form::submit('Submit', ['class'=>'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop
