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
            <div class="searchResults">

            </div>
        </div>


        <div class="profile">
            @if(Auth::user()->picture == null)
                <div class="profileImage">
                    {{mb_substr(Auth::user()->first_name,0,1) . mb_substr(Auth::user()->last_name,0,1)}}
                </div>
            @else
                <div class="profileImage pic" style="background:url({{url('/') . '/storage/' . Auth::user()->picture}}) center center no-repeat;">

                </div>
            @endif

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
    let searchTimer;
    let x = 500;
    let showLoader = true;

    $(document).ready(function() {
       $('.topbar .profileImage').on('click', function(){
           $('.topbar .profileMenu').fadeToggle(500);
       });

        $('input#search').on('keyup', function(e) {
            $('.loader').show();
            clearTimeout(searchTimer);
            $('.adminAutocomplete').empty();
            if(e.keyCode === 8) {
                x = 0;
            }
            else {
                x = 500;
            }

            let inputVal = $(this).val();
            if(inputVal !== '') {
                searchTimer = setTimeout(function(){ searchProjects(inputVal); }, x);
            }

        });

        $(document).bind('click', function(e) {
            if(!$(e.target).is('#search') && !$(e.target).is('a.linkResult')) {
                $('.searchResults').fadeOut(500, function() {
                    $(this).empty();
                });
            }
        });


    });
</script>
<script src="{{ asset('js/search.js') }}"></script>
</body>
</html>