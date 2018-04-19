@extends('layouts.app')

@section('content')
    <div id="loader">
        <div class="loader">

        </div>
    </div>

    <div id="mask">
    </div>

    @if($project->entries[0]->admin)
        <div id="customerFiles">
            <div id="closeFiles"><i class="fa fa-times"></i></div>
            <h4>
                Upload Files
            </h4>
            <hr>
            <form enctype="multipart/form-data" action="{{url('/user/files')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="project_id" value="{{$project->id}}">
                <input class="form-control" type="file" id="files" name="files[]" multiple required />
                <br />
                <textarea name="comments" class="form-control" id="" cols="30" rows="10"></textarea>
                <br>
                <button id="submitFileUpload" class="btn btn-secondary"><i class="fa fa-floppy-o" aria-hidden="true"></i> Submit</button>
            </form>

        </div>
    @endif



    @if($project->entries[0]->admin)
        <div class="comment" id="comment">
            <i class="fa fa-commenting-o" aria-hidden="true"></i> Comment Image
        </div>
    @endif

    <div class="container">
        @include('main.project.includes.status')
        <div class="row justify-content-center">
            <div class="col-md-3">
                @include('main.project.includes.info')
                @include('main.project.includes.revisions')
            </div>

            <div class="col-md-9">
                @include('main.project.includes.main')
            </div>
    </div>
    <br><br><br><br>
    @include('main.project.includes.footer')

@include('layouts/includes/scripts/viewProjScript')
  <script>
    let returnValues = {};
    window.items = [];
    window.bounds = [];
    @if($project->entries[0]->admin && !$project->completed)
    returnValues['linkAddy'] = "{{URL::to('/storage/projects/' . date('Y/F', strtotime($project->created_at)) . '/' . $project->file_path . '/' . $project->entries[0]->path . '/images/')}}";
    returnValues['data'] = {!! $project->entries[0]->files !!};
    @else
        returnValues = false;
    @endif
    $(document).ready(function() {
        populateCanvas(returnValues);
        $('div#comment').on('click', function() {
            let currEntry = $('div.entry.submissionEntry');
            let currImg = $('div.image.active', currEntry);
            $('#mask').fadeIn(500, function() {
                $('.textboxHolder', currImg).slideToggle(500);
            });

        });

        $('span.closeText').on('click', function() {
            let thisElm = $(this).parent();
            $(thisElm).slideToggle(500, function() {
                $('#mask').fadeOut(500);
            });
        });

        $('button#submit').on('click', function() {
            let images = [];
            let canvas = null;
            let comments = null;
            let activeElm = null;
            let x = 0;
            window.items.forEach(function() {
                let thisElm = {};
                canvas = window.items[x].getImage({rect: window.bounds[x]}).toDataURL();
                activeElm = $('div.submissionEntry .proj_'+x);
                comments = $('textarea', activeElm).val();
                thisElm['data'] = canvas;
                thisElm['comments'] = comments;
                images.push(thisElm);
                x++;
            });
            submitRevision(images, '{{$project->id}}');
        });

        $('button#submitFileUpload').on('click', function() {
            $('#loader').fadeIn(200);
        });

        $('#closeFiles').on('click', function() {
            $('#customerFiles').fadeOut(250, function() {
                $('#mask').fadeOut(250);
            });
        });

        $('button#approve').on('click', function() {
            let projectID = '{{$project->id}}';

            approveRevision(projectID);
        });

        $('button#new').on('click', function() {
            $('#mask').fadeIn(250, function() {
                $('#customerFiles').stop().fadeIn(250);
            })
        });

    });

  </script>
@endsection
