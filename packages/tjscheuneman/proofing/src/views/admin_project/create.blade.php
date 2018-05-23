@extends('proof::layouts.normal')

@section('title', 'Create Inital Project')

@section('content')
    <div id="loader">
        <div class="loader">

        </div>
    </div>
    @if(Session::has('flash_deleted'))
        <div class="alert alert-warning"><span class="glyphicon glyphicon-remove-circle"></span><em> {!! session('flash_deleted') !!}</em></div>
    @endif
    @if(Session::has('flash_created'))
        <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_created') !!}</em></div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-12">
            <a class="btn btn-brand-primary" href="{{url('admin')}}"><i class="fa fa-home"></i> Home</a>
            <a class="btn btn-brand-primary" href="{{url('admin/projects')}}"><i class="fa fa-pencil-square-o"></i> Projects</a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card topSpacing">
                <div class="card-header">
                    <p class="title">Other Awaiting Products in Order</p>
                   @foreach($otherProjects as $prod)
                        <a class="btn btn-secondary" href="{{url('admin/project') . '/' . $prod->file_path}}">{{$prod->project_name}}</a>
                   @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card topSpacing">
                <div class="card-body">
                    <h2>
                        Create Initial Upload for <span class="name_bg">{{$project->project_name}}</span>
                    </h2>
                    <br>
                    <form method="POST" action="{{ url('/admin/project') }}" enctype="multipart/form-data" id="submit">
                        {{csrf_field()}}

                        <input type="hidden" name="project_id" value="{{$project->id}}">

                        <div class="form-group">
                            <label for="pdf">PDF</label>
                            <input type="file" class="form-control" id="pdf" name="pdf" required>
                        </div>

                        <div class="form-group">
                            <label for="specs">Specifications</label>
                            <select name="specs" id="specs" class="form-control">
                                <option value="">---SELECT---</option>
                                @foreach($specs as $spec)
                                    <option value="{{$spec->id}}">{{$spec->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="comments">Comments (Use Markdown)</label>
                            <textarea name="comments" id="comments" class="form-control" cols="30" rows="10" placeholder="Comments Here"></textarea>
                        </div>

                        <hr>

                        <button type="submit" class="btn btn-submission">Submit</button>
                        <br>
                        @include('proof::layouts.errors')
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#loader').fadeOut(200);
            $('#specs').change(function() {
                let elm = $("option:selected", this);
                let val = elm.val();
                $('#loader').fadeIn(200, function() {

                    getSpecData(val, function(elm) {
                        if(elm) {
                            let specData = elm.specs;
                            let returnData = '###Specifications' + "   \n";
                            specData.forEach(function(elm) {
                                returnData += elm.spec.name + ': ' + elm.value + " \n";
                            });

                            $('#comments').val(returnData);
                            $('#loader').fadeOut(200);
                        }
                        return false;
                    });

                });

            });
        });

        function getSpecData(val, _callback) {
            axios.get('/admin/specs/schema/'+val)
                .then(function (response) {
                    let dataElm = response.data;
                    _callback(dataElm);
                    return true;

                })
                .catch(function (error) {
                    _callback(false);
                    return false;
                });
        }
    </script>
@endsection