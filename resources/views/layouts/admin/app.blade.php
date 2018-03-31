<html>
<head>
    <title>App Name - @yield('title')</title>

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
                TS
            </div>
            <div class="clear"></div>
            <div class="profileMenu">
                <span class="name">Thomas Scheuneman</span>
                <a href="#"><i class="fa fa-cogs" aria-hidden="true"></i> Settings</a>
            </div>
        </div>
    </div>
    <div class="clear"> </div>
    <hr>
    <div class="mainContent">
        @yield('content')
    </div>

</div>

</body>
</html>