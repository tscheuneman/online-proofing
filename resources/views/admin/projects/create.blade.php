@extends('layouts.admin.app')

@section('title', 'Create Project')

@section('content')
    @if(Session::has('flash_deleted'))
        <div class="alert alert-warning"><span class="glyphicon glyphicon-remove-circle"></span><em> {!! session('flash_deleted') !!}</em></div>
    @endif
    @if(Session::has('flash_created'))
        <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_created') !!}</em></div>
    @endif
    <h2>
        Create Project
    </h2>
    <br>
    <form method="POST" action="{{ url('/admin/categories') }}" enctype="multipart/form-data" id="submit">
        {{csrf_field()}}


        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{Request::old('name')}}" required>
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <select class="form-control" name="category" id="category">
                <option value="">----SELECT----</option>
                @foreach($cats as $cat)
                    <option value="{{$cat->id}}">{{$cat->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="admins">Assign Premedia</label>
            <input type="text" class="form-control" id="admins" name="admins" autocomplete="nope" />
            <div class="loader"></div>
            <div class="adminAutocomplete">

            </div>
            <div class="addedAdmins">

            </div>

            <br class="clear" />
        </div>

        <br class="clear" />

        <div class="form-group">
            <label for="users">Assign Customer (Use Email)</label>
            <input type="text" class="form-control" id="users" name="users" autocomplete="nope" />
            <div class="loaderSecond"></div>
            <div class="usersAutocomplete">

            </div>
            <div class="addedUsers">

            </div>

            <br class="clear" />
        </div>
        <br class="clear" />

        <hr>
        <button type="submit" class="btn btn-submission">Submit</button>
        <br>
        @include('layouts.errors')

    </form>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>

    <script>

        let adminSearchTimer, userSearchTimer;
        let x = 1000;
        let showLoader = true;

        $(document).ready(function() {
            jQuery(document).on('keyup', 'input#admins' , function(e) {
                $('.loader').show();
                clearTimeout(adminSearchTimer);
                $('.adminAutocomplete').empty();
                if(e.keyCode == 8) {
                    x = 0;
                }
                else {
                    x = 1000;
                }

                let inputVal = $(this).val();
                if(inputVal != '') {
                    adminSearchTimer = setTimeout(function(){ searchAdmins(inputVal); }, x);
                }
                else {
                    alert('test');
                }

            });

            jQuery(document).on('keyup', 'input#users' , function(e) {
                $('.loaderSecond').show();
                clearTimeout(adminSearchTimer);
                $('.usersAutocomplete').empty();
                if(e.keyCode == 8) {
                    x = 0;
                }
                else {
                    x = 1000;
                }

                let inputVal = $(this).val();
                if(inputVal != '') {
                    userSearchTimer = setTimeout(function(){ searchUsers(inputVal); }, x);
                }
                else {
                    alert('test');
                }

            });
        });

        function searchAdmins(inputVal) {
            let theURL = '/admin/search/admin?q=' + inputVal;
            let jqxhr = $.ajax(theURL)
                .done(function(data) {
                    $('.loader').hide();
                    populateAutofill(data);
                })
                .fail(function() {
                    alert( "error" );
                });
        }
        function searchUsers(inputVal) {
            let theURL = '/admin/search/users?q=' + inputVal;
            let jqxhr = $.ajax(theURL)
                .done(function(data) {
                    $('.loaderSecond').hide();
                    populateAutofillUsers(data);
                })
                .fail(function() {
                    alert( "error" );
                });
        }
        function populateAutofill(data) {
            let returnData = '<ul>';
                data.forEach(function(elm) {
                    returnData += '<li onClick="addAdmin(\''+elm.user.first_name+'\', \''+elm.user.last_name+'\', \''+elm.user.id+'\')">'+elm.user.first_name+' '+elm.user.last_name+'</li>';
                    console.log(elm);
                });
            returnData += '</ul>';
            $('.adminAutocomplete').html(returnData);
        }
        function populateAutofillUsers(data) {
            let returnData = '<ul>';
            data.forEach(function(elm) {
                returnData += '<li onClick="addUser(\''+elm.email+'\')">'+elm.email+'</li>';
                console.log(elm);
            });
            returnData += '</ul>';
            $('.usersAutocomplete').html(returnData);
        }
        function addAdmin(first, last, id) {
            $('.adminAutocomplete').empty();
            let returnVal = '<div class="theAdmin" data-id="'+id+'">'+first+' '+last+'</div>';
            $('.addedAdmins').append(returnVal);
        }
        function addUser(email) {
            $('input#users').val(email);
            $('.usersAutocomplete').empty();
        }
    </script>
@endsection