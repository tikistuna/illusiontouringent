@extends('admin.layouts.admin')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin">Dashboard</a>
                </li>
                @yield('breadcrumbs')
            </ol>
            <div class="card-body">
                <h1 class="pl-3">@yield('title')</h1>
                <hr>
                <div class="d-flex flex-row justify-content-around my-5">
                    @yield('form')
                </div>
            </div>
            <footer class="sticky-footer">
                <div class="container">
                    <div class="text-center">
                        <small>Copyright © Your Website 2018</small>
                    </div>
                </div>
            </footer>
            <!-- Scroll to Top Button-->
            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fa fa-angle-up"></i>
            </a>
        </div>
    </div>
@stop