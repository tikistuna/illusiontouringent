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
                        @if(isset($cities_prox))
                            @foreach($cities_prox as $city)
                                <div>
                                    <a href="{{route('public.cities', ['city' => $city])}}" class="dropdown-item text-white">{{$city}}</a>
                                </div>
                             @endforeach
                        @else
                            <div>
                                <a href="{{route('public.navigation')}}" class="dropdown-item text-white">Por Ciudad</a>
                            </div>
                            <div>
                                <a href="{{route('public.index')}}" class="dropdown-item text-white">Todos</a>
                            </div>
                        @endif
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Eventos Pasados</a>
                    <div class="dropdown-menu bg-dark drop_menu">
                        <div>
                            <a href="{{route('public.navigation', ['past' => 'true'])}}" class="dropdown-item text-white">Por Ciudad</a>
                        </div>
                        <div>
                            <a href="{{route('public.past')}}" class="dropdown-item text-white">Todos</a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>