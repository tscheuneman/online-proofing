<div class="row justify-content-center">
    <div class="col-md-12">
        @if($project->entries[0]->admin)
            @if(!$project->completed)
                <div class="status waiting">
                    Waiting on approval
                </div>
            @else
                <div class="status looked">
                    <strong>Project has been approved!</strong>
                </div>
            @endif
        @else
            <div class="status looked">
                Your revision is being reviewed by our premedia team!
            </div>
        @endif
    </div>
</div>