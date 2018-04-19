@if($project->entries[0]->admin && !$project->completed)
    <footer>
        <div class="container">
            <button id="submit" class="btn btn-primary">
                <i class="fa fa-share-square-o" aria-hidden="true"></i>
                Submit Revisions
            </button>
            &nbsp;&nbsp;&nbsp;
            |
            &nbsp;&nbsp;&nbsp;
            <button id="new" class="btn btn-secondary">
                <i class="fa fa-upload" aria-hidden="true"></i>
                Upload new Files
            </button>
            &nbsp;&nbsp;&nbsp;
            |
            &nbsp;&nbsp;&nbsp;
            <button id="approve" class="btn btn-submission">
                <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                Approve
            </button>
        </div>
    </footer>
@endif