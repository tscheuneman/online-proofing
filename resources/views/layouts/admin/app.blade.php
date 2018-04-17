<html>
<head>
    <title>{{ENV('APP_NAME')}} - @yield('title')</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="{{csrf_token()}}" name="csrf-token" />
</head>
<body>

<sidebar>
    @include('layouts.includes.admin.sidebar')
</sidebar>

<div id="content">
    <div class="topbar">
        <div class="search">
            <i class="fa fa-search" aria-hidden="true"></i>
            <input type="text" id="search" placeholder="Search for Projects or Users..."/>
        </div>

        <div class="profile">
            <div class="profileImage">
                {{mb_substr(Auth::user()->first_name,0,1) . mb_substr(Auth::user()->last_name,0,1)}}
            </div>
            <div class="clear"></div>
            <div class="profileMenu">

                <span class="name">{{Auth::user()->first_name . ' ' . Auth::user()->last_name}}</span>
                <a href="{{ url('/admin/profile') }}"><i class="fa fa-user" aria-hidden="true"></i> Profile</a>

                <a href="{{ url('logout') }}"
                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out" aria-hidden="true"></i>
                    {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>


            </div>
        </div>
    </div>
    <div class="clear"> </div>
    <hr>
    <div class="mainContent">
        @yield('content')
    </div>

</div>
<script>
    $(document).ready(function() {
       $('.profileImage').on('click', function(){
           $('.profileMenu').fadeToggle(500);
       });
    });
</script>
</body>
</html>