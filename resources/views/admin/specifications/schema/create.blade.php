@extends('layouts.admin.app')

@section('title', 'Create Specification Schema')

@section('content')
    @if(Session::has('flash_deleted'))
        <div class="alert alert-warning"><span class="glyphicon glyphicon-remove-circle"></span><em> {!! session('flash_deleted') !!}</em></div>
    @endif
    @if(Session::has('flash_created'))
        <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_created') !!}</em></div>
    @endif
    <h2>
        Create Specification Schema
    </h2>
    <br>
    <div id="mask">
    </div>
    <form method="POST" action="{{ url('/admin/specifications/schema') }}" enctype="multipart/form-data" id="submit" autocomplete="nope">
        {{csrf_field()}}

        <div class="form-group">
            <label for="spec_name">Schema Name</label>
            <input type="text" class="form-control" id="spec_name" name="spec_name" value="{{Request::old('spec_name')}}" required>
        </div>

        <div class="form-group">
            <label for="content_type">Add Specifications</label>
            <select class="form-control" name="content_type" id="content_type">
                <option value="">----SELECT----</option>
                @foreach($specs as $spec)
                    <option data-default="{{$spec->default}}" value="{{$spec->id}}">{{$spec->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="holder" style="position:relative;">
            <div id="chooseVals" class="dropdown-menu">
                <strong id="dropdown_name_spec"></strong>
                <div class="dropdown-divider"></div>

                <div class="form-group">
                        <label for="exampleDropdownFormEmail1">Default Value</label>
                        <input type="text" class="form-control" id="defaultValue" placeholder="default value">
                        <input type="hidden" id="spec_id" />
                    </div>
                    <div class="dropdown-divider"></div>

                    <button id="addSpec" type="submit" class="btn btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add</button>
            </div>
        </div>

        <div id="specElements">

        </div>

        <br class="clear" />
        <br>

        <input type="hidden" id="jsonSpecs" name="jsonSpecs" value="">
        <button id="sendButton" type="submit" class="btn btn-submission">
            Submit
        </button>

        <br>
        @include('layouts.errors')
    </form>
    <script>
        $(document).ready(function() {
            $('#content_type').change(function() {
                let elm = $("option:selected", this);
                let defaultVal = elm.data('default');
                let val = elm.val();
                let name = elm.text();
                emptySpecVals();
                populateSpecVals(val, name, defaultVal)
            });

            $('#addSpec').on('click', function(e) {
                e.preventDefault();
                let parentElm = $('#chooseVals');
                let defaultVal = $('#defaultValue', parentElm).val();
                let spec_id = $('#spec_id', parentElm).val();
                let name = $('#dropdown_name_spec', parentElm).text();

                parentElm.hide(1);

                validateSpecification(defaultVal, spec_id, function(elm) {
                    if(elm) {
                        createSpec(defaultVal, spec_id, name);
                    }
                    return false;
                });
            });
            $('#sendButton').on('click', function(e) {
                let returnData = [];
                $('.specElement').each(function() {
                    let holder = {};

                    let id = $(this).data('id');
                    let value = $(this).data('value');
                        holder['id'] = id;
                        holder['value'] = value.toString();

                        returnData.push(holder);
                });
                let jsonData = JSON.stringify(returnData);
                $('#jsonSpecs').val(jsonData);
            });
        });

        function populateSpecVals(id, name, defaultValue) {
            let parentElm = $('#chooseVals');
                $('#dropdown_name_spec', parentElm).text(name);
                $('#defaultValue', parentElm).val(defaultValue);
                $('#spec_id', parentElm).val(id);
                parentElm.show(5);
            $('#defaultValue', parentElm).focus().select();
        }
        function emptySpecVals() {
            let parentElm = $('#chooseVals');
            $('#dropdown_name_spec', parentElm).text('');
            $('#defaultValue', parentElm).val('');
            $('#spec_id', parentElm).val('');
        }
        function validateSpecification(val, id, _callback) {
            axios.post('/admin/validate/spec', {
                value: val,
                id: id
            })
                .then(function (response) {
                    console.log(response);
                    let returnData = response.data;
                    if (returnData.status === "Success") {
                        _callback(true);
                        return true;
                    }
                    else {
                        _callback(false);
                        return false;
                    }

                })
                .catch(function (error) {
                    _callback(false);
                    return false;
                });
        }
        function createSpec(val, id, name) {
            let element = '<div data-id="'+id+'" data-value="'+val+'" class="specElement">' +
                '<div onClick="removeElm(this)" class="removeSpec"><i class="fa fa-times" aria-hidden="true"></i></div>' +
                '<h4>'+name+'</h4>' +
                '<span>Value: <strong>'+val+'</strong></span>' +
                '</div>';

            $('#specElements').append(element);
            $('#content_type').val("");
        }
        function removeElm(elm) {
            let parent = $(elm).parent();
            $(parent).fadeOut(500, function() {
                $(this).remove();
            });
        }
    </script>
@endsection