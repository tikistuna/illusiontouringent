<?php
	$cities_prox = App\Models\City::whereHas('events', function ($query){
		$query->whereDate('date', '>=', Carbon\Carbon::today()->toDateString());
	})->orderBy('name')->pluck('name');
	$cities_past = App\Models\City::whereHas('events', function ($query){
		$query->whereDate('date', '<=', Carbon\Carbon::today()->toDateString());
	})->orderBy('name')->pluck('name');
?>
<nav class="navbar navbar-expand-md bg-dark navbar-dark fixed-top">
    <div class="container">
        <img class="img-fluid" src="/assets/logos/logo.png" width="92" height="66">
        <a href="{{route('public.index')}}" class="navbar-brand">L.J. Productions</a>
        <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="{{route('public.index')}}" class="nav-link">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Siguientes Eventos</a>
                    <div class="dropdown-menu bg-dark drop_menu">
                        @foreach($cities_prox as $city)
                            <div>
                                <a href="{{route('public.cities', ['city' => $city])}}" class="dropdown-item text-white">{{$city}}</a>
                            </div>
                        @endforeach
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Eventos Pasados</a>
                    <div class="dropdown-menu bg-dark drop_menu">
                        @foreach($cities_past as $city)
                            <div>
                                <a href="{{route('public.past', ['city' => $city])}}" class="dropdown-item text-white">{{$city}}</a>
                            </div>
                        @endforeach
                        <div>
                            <a href="{{route('public.past')}}" class="dropdown-item text-white">Todos</a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>