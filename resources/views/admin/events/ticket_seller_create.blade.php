@extends('admin.layouts.create')

@section('title')
    Add a Ticket Seller to {{$event->name}} in {{$event->city->name}}
@stop

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="/admin/events">Events</a>
    </li>
    <li class="breadcrumb-item active">Link Ticket Seller</li>
@stop

@section('form')
    <div class="card bg-dark card-form">
        <div class="card-body text-white">
            <h4 class="text-white font-weight-bold text-center">Link a Ticket Seller</h4>
            <hr>
            <div class="row mt-3">
                <div class="col-sm-9 mx-auto">
                    {!! Form::open(['method' => 'POST', 'action' => ['EventController@eventTicketSeller', $event->id]]) !!}

                    <div class="form-group">
                        {!! Form::label('ticket_seller_id', 'Ticket Seller:') !!}
                        {!! Form::select('ticket_seller_id', array(''=>'Select a Ticket Seller')  + $ticket_sellers , null,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('website', 'Website:') !!}
                        {!! Form::text('website', null, ['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <a href="{{route('events.index')}}" class="btn btn-link text-light">Cancel</a>
                        {!! Form::submit('Link Seller', ['class'=>'btn btn-primary']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection