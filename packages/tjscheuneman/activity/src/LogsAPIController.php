<?php

namespace Tjscheuneman\ActivityEvents;

use App\Http\Controllers\Controller;
use App\Services\Project\ProjectLogic;

class LogsAPIController extends Controller
{
    public function getProjectLogs($id) {
        $project = ProjectLogic::find_path($id);
        return ActivityEvent::getFromProject($project);
    }
}