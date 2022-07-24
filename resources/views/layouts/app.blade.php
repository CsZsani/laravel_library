<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fa.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light shadow-sm"
        @guest
            style="background-color: #b8fdff;"
        @else
            style="background-color: {{ Auth::user()->isLibrarian() ? "#f79372" : "#a0d980" }};"
        @endguest>
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @guest
                            <li class="nav-item">
                                <span class="nav-link badge badge-primary">Vendég felület</span>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('books*')) ? 'active' : '' }}" href="{{ route('books.index') }}">Könyvek</a>
                            </li>
                        @else
                        
                        @if (Auth::user()->isLibrarian())
                            <li class="nav-item">
                                <span class="nav-item badge badge-danger">Könyvtáros felület</span>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('books*')) ? 'active' : '' }}" href="{{ route('books.index') }}">Könyvek</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('librarian*')) ? 'active' : '' }}" href="{{ route('librarian.borrow.pending') }}">Kölcsönzések</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <span class="badge badge-success">Olvasói felület</span>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('books*')) ? 'active' : '' }}" href="{{ route('books.index') }}">Könyvek</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('reader*')) ? 'active' : '' }}" href="{{ route('reader.borrow.pending') }}">Kölcsönzéseim</a>
                            </li>
                        @endif
                        @endguest
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item ">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Belépés') }}</a>
                                </li>
                            @endif
                            
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Regisztráció') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item active">
                                <a class="nav-link disabled">{{ Auth::user()->name }}</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    @if (Auth::user()->isLibrarian())
                                            <span class="badge badge-danger">Könyvtáros</span>
                                    @else
                                            <span class="badge badge-success">Olvasó</span>
                                    @endif
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile') }}">
                                        {{ __('Profil') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Kilépés') }}
                                    </a>
                                    

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        <footer>
            <div class="container mb-4">
                <hr>
                <div class="d-flex flex-column align-items-center">
                    <div>
                        <span class="small">Könyvtár alkalmazás</span>
                        <span class="mx-1">·</span>
                        <span class="small">Laravel {{ app()->version() }}</span>
                        <span class="mx-1">·</span>
                        <span class="small">PHP {{ phpversion() }}</span>
                    </div>

                    <div>
                        <span class="small">Szerveroldali webprogramozás 2020-21-2 beadandó</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
