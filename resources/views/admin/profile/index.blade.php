@extends('layouts.admin.app')

@section('title', 'User Profile')

@section('content')
    @if(Session::has('flash_deleted'))
        <div class="alert alert-warning"><span class="glyphicon glyphicon-remove-circle"></span><em> {!! session('flash_deleted') !!}</em></div>
    @endif
    @if(Session::has('flash_created'))
        <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_created') !!}</em></div>
    @endif
    <input type="hidden" id="user" value="{{Auth::id()}}">
    <div id="customerFiles">
        <div id="closeFiles"><i class="fa fa-times"></i></div>
        <h4>
            Upload Image
        </h4>
        <hr>
        <form enctype="multipart/form-data" action="{{url('/user/image')}}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="user_id" value="{{$admin->id}}">
            <input class="form-control" type="file" id="files" name="files" required />
            <br />
            <button id="submitFileUpload" class="btn btn-secondary"><i class="fa fa-floppy-o" aria-hidden="true"></i> Submit</button>
        </form>
    </div>

    <h1>User Details</h1>
    <hr>
    <div id="profile">
        <div class="profileImage">
            @if($admin->picture == null)
                <div class="picture">
                    {{mb_substr($admin->first_name,0,1) . mb_substr($admin->last_name,0,1)}}
                    <div class="addImage">+ Add</div>
                </div>

            @else
                <div class="picture pic" style="background:url({{url('/') . '/storage/' . $admin->picture}}) center center no-repeat;">
                    <div class="addImage">+ Change</div>
                </div>

            @endif
        </div>
        <div class="profileInfo">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a onClick="show('1', this)" class="nav-link active show1" href="#">Profile</a>
                </li>
                <li class="nav-item">
                    <a onClick="show('2', this)" class="nav-link show2" href="#">Activity</a>
                </li>
            </ul>

            <div id="show1" class="showElm active">
                <table class="table">
                    <thead>
                    <tr>
                        <td colspan="2">
                            Contact Info
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            First Name
                        </td>
                        <td>
                            <input id="firstName" type="text" value="{{$admin->first_name}}" class="profileEdit form-control" />
                            {{$admin->first_name}}
                            <span class="editProfileField" id="editFirstName"><i class="fa fa-pencil" aria-hidden="true"></i></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Last Name
                        </td>
                        <td>
                            <input id="lastName" type="text" value="{{$admin->last_name}}" class="profileEdit form-control" />
                            {{$admin->last_name}}
                            <span class="editProfileField" id="editLastName"><i class="fa fa-pencil" aria-hidden="true"></i></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Email
                        </td>
                        <td>
                            {{$admin->email}}
                        </td>
                    </tr>
                    </tbody>
                    <thead>
                    <tr>
                        <td colspan="2">
                            Additional Info
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            Organization
                        </td>
                        <td>
                            {{$admin->org}}
                        </td>
                    </tr>
                    </tbody>
                </table>

                <button id="saveProfileInfo" class="btn btn-primary">
                    Save
                </button>

            </div>

            <div class="showElm" id="show2">
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th>
                            Date
                        </th>
                        <th>
                            Activity
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($activity as $ac)
                        <tr>
                            <td>
                                {{date('F jS g:i a', strtotime($ac->created_at))}}
                            </td>
                            <td>
                                {{$ac->action}} for <strong>{{$ac->project->order_name->job_id . ' | ' . $ac->project->project_name}}</strong>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.picture').height($('.picture').width()).css('line-height' , $('.picture').height() + 'px');

            $('.addImage').on('click', function() {
               showProfileImage();
            });

            $('#closeFiles').on('click', function() {
                showProfileImage();
            });


            $('.profileImage .picture').hover(function() {
                $('.addImage', this).stop().fadeIn(200);
            }, function() {
                $('.addImage', this).stop().fadeOut(200);
            });

            $('.editProfileField').on('click', function() {
                let parent = $(this).parent();
                $('input', parent).show(5).focus();
            });

            $('#saveProfileInfo').on('click', function() {
                let fName = $('#firstName').val();
                let lName = $('#lastName').val();
                let user = $('#user').val();

                axios.post('/admin/profile', {
                    first_name: fName,
                    last_name: lName,
                    user: user
                })
                    .then(function (response) {
                        let returnData = response.data;
                        if (returnData.status === "Success") {
                            alert(returnData.message);
                            location.reload();
                        }
                        else {
                            alert(returnData.message);
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            });
        });

        function showProfileImage() {
            $('#customerFiles').stop().fadeToggle(500);
        }
        function show(id, elm) {
            if($(elm).hasClass('active')) {
                return false;
            }
            $('.nav-item a').removeClass('active');
            $('.nav-item a.show'+id).addClass('active');
            $('.showElm.active').fadeOut(250, function() {
                $(this).removeClass('active');
                $('#show'+id).fadeIn(250, function() {
                    $(this).addClass('active');
                });
            });
        }
    </script>
@endsection