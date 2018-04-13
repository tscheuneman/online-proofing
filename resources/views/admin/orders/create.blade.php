@extends('layouts.admin.app')

@section('title', 'Create Order')

@section('content')
    @if(Session::has('flash_deleted'))
        <div class="alert alert-warning"><span class="glyphicon glyphicon-remove-circle"></span><em> {!! session('flash_deleted') !!}</em></div>
    @endif
    @if(Session::has('flash_created'))
        <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_created') !!}</em></div>
    @endif
    <h2>
        Create Order
    </h2>
    <br>
    <div id="mask">
        <div class="container topSpacing">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-check-square-o" aria-hidden="true"></i>
                            Add Part
                            <div id="closeHead">
                                x
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="projectName">Name</label>
                                <input type="text" class="form-control" id="projectName" name="projectName" />
                            </div>
                            <button id="savePart" class="btn btn-secondary"><i class="fa fa-plus" aria-hidden="true"></i> Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ url('/admin/orders') }}" enctype="multipart/form-data" id="submit">
        {{csrf_field()}}

        <div class="form-group">
            <label for="job_id">Job ID</label>
            <input type="text" class="form-control" id="job_id" name="job_id" value="{{Request::old('job_id')}}" required>
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
            <div id="addPart" class="btn btn-primary">+ Add Part</div>
        </div>

        <div class="partHolder">

        </div>

        <br class="clear" />

        <div class="form-group">
            <label for="notify_admins">Notify Premedia</label>
            <input type="checkbox" class="form-control left" id="notify_admins" name="notify_admins" autocomplete="nope" checked />
        </div>

        <div class="form-group">
            <label for="admins">Assign Premedia</label>
            <input type="text" class="form-control" id="admins" name="admins" autocomplete="nope" />
            <div class="loader"></div>
            <div class="adminAutocomplete">

            </div>
            <div class="addedAdmins">

            </div>
        </div>

        <br class="clear" />

        <div class="form-group">
            <label for="notify_users">Notify Customers</label>
            <input type="checkbox" class="form-control left" id="notify_users" name="notify_users" autocomplete="nope" checked />
        </div>

        <div class="form-group">
            <label for="users">Assign Customer (Use Email)</label>
            <input type="text" class="form-control" id="users" name="users" autocomplete="nope" />
            <div class="loaderSecond"></div>
            <div class="usersAutocomplete">

            </div>
            <div class="addedUsers">

            </div>
        </div>
        <br class="clear" />
        <div class="form-group">
            <label for="hidden">Only show to assigned customer?</label>
            <input type="checkbox" class="form-control left" id="hidden" name="hidden" autocomplete="nope" />
        </div>

        <hr>
        <input type="hidden" id="adminValues" name="adminValues" />
        <input type="hidden" id="userValues" name="userValues" />
        <input type="hidden" id="projectValues" name="projectValues" />
        <button id="submit" type="submit" class="btn btn-submission">Submit</button>
        <br>
        @include('layouts.errors')
    </form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>

    <script>

        let adminSearchTimer, userSearchTimer;
        let x = 500;
        let showLoader = true;

        $(document).ready(function() {

            jQuery(document).on('keyup', 'input#admins' , function(e) {
                $('.loader').show();
                clearTimeout(adminSearchTimer);
                $('.adminAutocomplete').empty();
                if(e.keyCode === 8) {
                    x = 0;
                }
                else {
                    x = 500;
                }

                let inputVal = $(this).val();
                if(inputVal !== '') {
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
                if(e.keyCode === 8) {
                    x = 0;
                }
                else {
                    x = 1000;
                }

                let inputVal = $(this).val();
                if(inputVal !== '') {
                    userSearchTimer = setTimeout(function(){ searchUsers(inputVal); }, x);
                }
                else {
                    alert('test');
                }

            });

            $('#submit').submit(function() {
                if(!$('.theProject').length) {
                    alert("Please add a order part");
                    return false;
                }

                if(!$('.theAdmin').length) {
                    alert('Please Assign a Premedia Member');
                    return false;
                }
                if(!$('.theUser').length) {
                    alert('Please Assign a Customer');
                    return false;
                }
                let returnVal = [];
                $('.theAdmin').each(function() {
                    returnVal.push($(this).data('id'));
                });
                returnVal = JSON.stringify(returnVal);
                $('#adminValues').val(returnVal);

                let returnVal2 = [];
                $('.theUser').each(function() {
                    returnVal2.push($(this).data('id'));
                });
                returnVal2 = JSON.stringify(returnVal2);
                $('#userValues').val(returnVal2);

                let returnVal3 = [];
                $('.theProject').each(function() {
                    returnVal3.push($(this).data('name'));
                });
                returnVal3 = JSON.stringify(returnVal3);
                $('#projectValues').val(returnVal3);
            });

            $('#addPart').on('click', function() {
                $('#projectName').val('');
                $('#mask').fadeIn(500, function() {
                    $('#projectName').focus();
                });
            });

            $('#savePart').on('click', function() {
                saveVal();
            });
            $("#projectName").keydown(function (e) {
                if (e.keyCode === 13) {
                    saveVal();
                }
            });

            $('#closeHead').on('click', function() {
                $('#mask').fadeOut(500, function() {
                    $('#projectName').val('');
                });
            });
        });

        function saveVal() {
            let value = $('#projectName').val();
            if(value !== "") {
                let returnVal = '<div class="theProject" data-name="'+value+'">'+value+'</div>';
                $('.partHolder').append(returnVal);
                $('#projectName').val('');
                $('#projectName').focus();
                return;
            }
            alert("Please provide a name");
        }

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
                returnData += '<li onClick="addAdmin(\''+elm.user_search.first_name+'\', \''+elm.user_search.last_name+'\', \''+elm.id+'\')">'+elm.user_search.first_name+' '+elm.user_search.last_name+'</li>';
                console.log(elm);
            });
            returnData += '</ul>';
            $('.adminAutocomplete').html(returnData);
        }
        function populateAutofillUsers(data) {
            let returnData = '<ul>';
            data.forEach(function(elm) {
                returnData += '<li onClick="addUser(\''+elm.email+'\', \''+elm.id+'\')">'+elm.email+'</li>';
                console.log(elm);
            });
            returnData += '</ul>';
            $('.usersAutocomplete').html(returnData);
        }
        function addAdmin(first, last, id) {
            $('.adminAutocomplete').empty();
            let returnVal = '<div class="theAdmin" data-id="'+id+'">'+first+' '+last+'</div>';
            $('.addedAdmins').append(returnVal);
            $('input#admins').val('');
        }
        function addUser(email, id) {
            $('.usersAutocomplete').empty();
            let returnVal = '<div class="theUser" data-id="'+id+'">'+email+'</div>';
            $('.addedUsers').append(returnVal);
            $('input#users').val('');
        }
    </script>
@endsection