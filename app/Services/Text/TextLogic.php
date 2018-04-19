<?php

namespace App\Services\Text;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Text;
use App\Project;

class TextLogic {
    protected $text;

    public function __construct(Text $text)
    {
        $this->text = $text;
    }

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