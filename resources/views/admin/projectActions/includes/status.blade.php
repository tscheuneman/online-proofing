@if($project->completed)
    <button id="showMessages" class="btn btn-primary circleButton"><i class="fa fa-comments" aria-hidden="true"></i></button>
    <div class="row">
        <h1>
            {{$project->project_name}} - <span class="approved">Approved</span>
        </h1>
    </div>
@else
    <button id="showMessages" class="btn btn-primary circleButton"><i class="fa fa-comments" aria-hidden="true"></i></button>
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if($project->entries[0]->admin)
                <div class="status waiting">
                    Waiting on user action
                </div>
            @else
                <div class="status looked">
                    Proof Ready to View!
                </div>
                <a class="btn btn-submission" href="{{url('admin/project/add') . '/' . $project->file_path}}"><i class="fa fa-plus"></i> Create Revision </a>
                <br />
                <br>
            @endif
        </div>
    </div>
    <div class="row">
        <h1>
            {{$project->project_name}}
        </h1>
    </div>
@endif