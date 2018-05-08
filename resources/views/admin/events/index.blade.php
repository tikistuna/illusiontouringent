@extends('admin.layouts.admin')
@section('scripts')
    <script src="/js/admin_events.js"></script>
@stop
@section('content')
    <div class="content-wrapper">
        <div class="container-fluid">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Events</li>
            </ol>
            <div id="app"></div>
        </div>
    </div>
@stop
