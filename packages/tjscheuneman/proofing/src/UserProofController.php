<?php

namespace Tjscheuneman\Proofing;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Project;
use Illuminate\Support\Facades\Auth;

use Tjscheuneman\ActivityEvents\ActivityEvent;


class UserProofController extends Controller {

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::where('file_path', '=', $id)->first();
        if($project != null) {
            if($project->active) {
                $thisProject = Project::where('file_path', '=', $id)->first();

                if(\Auth::check()) {
                    $isUser = false;
                    foreach($thisProject->order->users as $user) {
                        if($user->user->id == Auth::id()) {
                            $isUser = true;
                            break;
                        }
                    }
                    if($isUser) {
                        ActivityEvent::create($thisProject, Auth::user(), 'Viewed Project');
                        return view('proof::user_project.index',
                            [
                                'project' => $thisProject,

                            ]);
                    }

                }
                if(!$thisProject->order->hidden) {
                    return view('proof::user_project.guest',
                        [
                            'project' => $thisProject,

                        ]);
                }
                return abort(404, 'cannot find');

            }
        } else {
            return redirect()->back();
        }
    }
}