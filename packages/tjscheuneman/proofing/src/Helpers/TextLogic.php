<?php

namespace Tjscheuneman\Proofing\Helpers;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use Tjscheuneman\Proofing\Text;
use App\Project;

class TextLogic {
    protected $text;

    /**
     * TextLogic Constructor
     *
     * @param Text $text
     * @return void
     */
    public function __construct(Text $text)
    {
        $this->text = $text;
    }

    /**
     * Create text entry
     *
     * @param \App\Project $project
     * $param string $data
     * @return mixed
     */
    public static function create(Project $project, $data) {

        $search = Text::where('project_id', $project->id)->first();

        if($search == null) {
            $text = new Text();
            $text->project_id = $project->id;
            $text->data = $data;

            $text->save();

            return new TextLogic($text);
        }
        return false;

    }


}

?>