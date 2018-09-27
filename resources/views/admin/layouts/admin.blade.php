@php
$user = \Illuminate\Support\Facades\Auth::user();
$notifications = $user->notifications;
@endphp
<!DOCTYPE html>
<html lang="en" class="fa-events-icons-ready">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Admin Dashboard</title>
        <!-- Bootstrap core CSS-->
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <!-- Custom fonts for this template-->
        <link href="/css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <!-- Custom styles for this template-->
        <link href="/css/admin.css" rel="stylesheet">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @yield('head')
    </head>

    <body class="fixed-nav sticky-footer bg-dark" id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
            <img src="/assets/logos/logo.png" alt="" height="47" width="60"><a class="navbar-brand" href="/admin">Illusion Touring Entertainment</a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
                        <a class="nav-link" href="/admin">
                            <i class="fa fa-fw fa-dashboard"></i>
                            <span class="nav-link-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Menu Levels">
                        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseMulti" data-parent="#exampleAccordion">
                            <i class="fa fa-fw fa-sitemap"></i>
                            <span class="nav-link-text">Resources</span>
                        </a>
                        <ul class="sidenav-second-level collapse" id="collapseMulti">
                            <li>
                                <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#events-dropdown">Events</a>
                                <ul class="sidenav-third-level collapse" id="events-dropdown">
                                    <li>
                                        <a href="/admin/events">All events</a>
                                    </li>
                                    <li>
                                        <a href="/admin/events/create">Create Event</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#ticket_sellers-dropdown">Ticket Sellers</a>
                                <ul class="sidenav-third-level collapse" id="ticket_sellers-dropdown">
                                    <li>
                                        <a href="/admin/ticket_sellers">All Ticket Sellers</a>
                                    </li>
                                    <li>
                                        <a href="/admin/ticket_sellers/create">Create Ticket Seller</a>
                                    </li>
                                    <li>
                                        <a href="/admin/events/statistics">Statistics</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#resources-dropdown">Resources</a>
                                <ul class="sidenav-third-level collapse" id="resources-dropdown">
                                    <li>
                                        <a href="/admin/cities">Cities</a>
                                    </li>
                                    <li>
                                        <a href="/admin/venues">Venues</a>
                                    </li>
                                    <li>
                                        <a href="/admin/folders">Folders</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Excel">
                        <a class="nav-link" href="/admin/excel">
                            <i class="fa fa-fw fa-link"></i>
                            <span class="nav-link-text">Excel</span>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav sidenav-toggler">
                    <li class="nav-item">
                        <a class="nav-link text-center" id="sidenavToggler">
                            <i class="fa fa-fw fa-angle-left"></i>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle mr-lg-2" id="messagesDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-fw fa-envelope"></i>
                            <span class="d-lg-none">Messages
              <span class="badge badge-pill badge-primary">12 New</span>
            </span>
                            <span class="indicator text-primary d-none d-lg-block">
              <i class="fa fa-fw fa-circle"></i>
            </span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="messagesDropdown">
                            <h6 class="dropdown-header">New Messages:</h6>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">
                                <strong>David Miller</strong>
                                <span class="small float-right text-muted">11:21 AM</span>
                                <div class="dropdown-message small">Hey there! This new version of SB Admin is pretty awesome! These messages clip off when they reach the end of the box so they don't overflow over to the sides!</div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">
                                <strong>Jane Smith</strong>
                                <span class="small float-right text-muted">11:21 AM</span>
                                <div class="dropdown-message small">I was wondering if you could meet for an appointment at 3:00 instead of 4:00. Thanks!</div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">
                                <strong>John Doe</strong>
                                <span class="small float-right text-muted">11:21 AM</span>
                                <div class="dropdown-message small">I've sent the final files over to you for review. When you're able to sign off of them let me know and we can discuss distribution.</div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item small" href="#">View all messages</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle mr-lg-2" id="alertsDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-fw fa-bell"></i>
                            <span class="d-lg-none">Alerts
              <span class="badge badge-pill badge-warning">{{$notifications->count()}} New</span>
            </span>
                            <span class="indicator text-warning d-none d-lg-block">
                                <i class="fa fa-fw fa-circle"></i>
                            </span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="alertsDropdown">
                            <h6 class="dropdown-header">New Alerts:</h6>



                            @foreach($notifications as $notification)
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">
                                <span class="text-danger">
                                    <strong>
                                        <i class="fa fa-exclamation-triangle fa-fw"></i>
                                        {{$notification->data['title']}}:
                                    </strong>
                                </span>
                                    <span class="small float-right text-muted">{{$notification->created_at->format('M  j, g:ia')}}</span>
                                    <div class="dropdown-message small">{{$notification->data['content']}}</div>
                                </a>
                            @endforeach
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item small" href="#">View all notifications</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <form class="form-inline my-2 my-lg-0 mr-lg-2">
                            <div class="input-group">
                                <input class="form-control" type="text" placeholder="Search for...">
                                <span class="input-group-append">
                <button class="btn btn-primary" type="button">
                  <i class="fa fa-search"></i>
                </button>
              </span>
                            </div>
                        </form>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="modal" data-target="#exampleModal">
                            <i class="fa fa-fw fa-sign-out"></i>Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
        @yield('content')
        <!-- Logout Modal-->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            {{ csrf_field() }}
                            {!! Form::submit('Logout', ['class'=>'btn btn-primary']) !!}
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <script src="/js/jquery.min.js"></script>
        <script src="/js/tether.min.js"></script>
        <script src="/js/bootstrap.bundle.min.js"></script>
        <script src="/js/jquery.easing.min.js"></script>
        <script src="/js/Chart.min.js"></script>
        <script src="/js/sb-admin.min.js"></script>
        @yield('scripts')
    </body>

</html>

