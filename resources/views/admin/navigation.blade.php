<?php
    use App\Models\City;
	$cities = City::orderBy('name')->pluck('name');
?>
<nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
    <div class="container">
        <img class="img-fluid" src="/assets/logos/logo.png" width="102" height="73">
        <a href="{{route('events.index')}}" class="navbar-brand">L.J. Productions</a>
        <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            @if(Auth::check())
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a href="{{route('events.index')}}" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Events</a>
                        <div class="dropdown-menu bg-dark">
                            <div>
                                <a href="{{route('events.create')}}" class=" dropdown-item text-white">New Event</a>
                            </div>
                            @foreach($cities as $city)
                                <div>
                                    <a href="{{route('events.index', ['city' => $city])}}" class="dropdown-item text-white">{{$city}}</a>
                                </div>
                            @endforeach
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Posters</a>
                        <div class="dropdown-menu bg-dark">
                            <div>
                                <a href="{{route('photos.create')}}" class="dropdown-item text-white">New Poster</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Venues</a>
                        <div class="dropdown-menu bg-dark">
                            <div>
                                <a href="{{route('venues.index')}}" class="dropdown-item text-white">All Venues</a>
                            </div>
                            <div>
                                <a href="{{route('venues.create')}}" class="dropdown-item text-white">New Venue</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Cities</a>
                        <div class="dropdown-menu bg-dark">
                            <div>
                                <a href="{{route('cities.index')}}" class="dropdown-item text-white">All Cities</a>
                            </div>
                            <div>
                                <a href="{{route('cities.create')}}" class="dropdown-item text-white">New City</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Ticket Sellers</a>
                        <div class="dropdown-menu bg-dark">
                            <div>
                                <a href="{{route('ticket_sellers.index')}}" class="dropdown-item text-white">All Ticket Sellers</a>
                            </div>
                            <div>
                                <a href="{{route('ticket_sellers.create')}}" class="dropdown-item text-white">New Ticket Seller</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Folders</a>
                        <div class="dropdown-menu bg-dark">
                            <div>
                                <a href="{{route('folders.index')}}" class="dropdown-item text-white">All Folders</a>
                            </div>
                            <div>
                                <a href="{{route('folders.create')}}" class="dropdown-item text-white">New Folder</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('winners')}}" class="nav-link text-white">
                            Select winner
                        </a>
                    </li>
                    <li class="nav-item">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            {{ csrf_field() }}
                            {!! Form::submit('Logout', ['class'=>'btn btn-link nav-link']) !!}
                        </form>
                    </li>

                </ul>
            @endif
        </div>
    </div>
</nav>