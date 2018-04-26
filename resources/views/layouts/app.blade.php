<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/user.js') }}"></script>
    <script src="{{ asset('js/project.js') }}"></script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <img id="logo" src="{{url('/assets/logo.png')}}" alt="" />
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ ENV('APP_NAME', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li><a class="nav-link" href="{{ url('login') }}"><i class="fa fa-sign-in" aria-hidden="true"></i> {{ __('Login') }}</a></li>
                        @else
                            <li class="nav-item dropdown">
                                <div id="dLabel" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="notification">
                                    <i class="fa fa-bell" aria-hidden="true"></i>
                                    @if(count($number) > 0)
                                        <div id="notification_number">
                                            {{count($number)}}
                                        </div>
                                    @endif
                                </div>
                                @if(count($number) > 0)
                                    <div class="dropdown-menu" id="notification_info" aria-labelledby="dLabel">
                                        <div>
                                            <p class="title"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                 Awating Action</p>
                                            <div class="dropdown-divider"></div>
                                                @foreach(json_decode($number) as $proj)
                                                    <a class="dropdown-item" href="{{url('/project') . '/' . $proj->file_path}}">{{$proj->order->job_id}} | {{$proj->project_name}}</a>
                                                @endforeach
                                        </div>
                                    </div>
                                @endif
                            </li>
                            <li class="nav-item">
                                @if(Auth::user()->picture == null)
                                    <div class="navPic">
                                        {{mb_substr(Auth::user()->first_name,0,1) . mb_substr(Auth::user()->last_name,0,1)}}
                                    </div>
                                @else
                                    <div class="navPic pic" style="background:url({{url('/') . '/storage/' . Auth::user()->picture}}) center center no-repeat;">

                                    </div>
                                @endif
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->first_name . ' ' . Auth::user()->last_name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="profile">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                        Profile
                                    </a>
                                    <a class="dropdown-item" href="{{ url('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fa fa-sign-out" aria-hidden="true"></i>
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
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
    </div>
</body>
</html>
