@extends('admin.layouts.create')

@section('title')
    Create New Ticket Seller
@stop

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="/admin/ticket_sellers">Ticket Sellers</a>
    </li>
    <li class="breadcrumb-item active">Create</li>
@stop
@section('form')
    <div class="card bg-dark card-form">
        <div class="card-body text-white">
            <h4 class="text-white font-weight-bold text-center">Create Ticket Seller</h4>
            <hr>
            <div class="row mt-3">
                <div class="col-sm-9 mx-auto">
                    {!! Form::open(['method' => 'POST', 'action' => 'TicketSellerController@store']) !!}
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
    </div>
@endsection