@extends('admin.layouts.create')

@section('title')
   Create New Event
@stop

@section('head')
    <meta name="appUrl" content="{{ env('APP_URL') }}">
    <meta name="date" content="{{ \Carbon\Carbon::now()->formatLocalized('%b %e, %Y %l:%M%p') }}">
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
                    <div id="app"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="/js/admin_create_events.js"></script>
@stop
