<?php

namespace App\Services\Entry;

use App\Entry;

class EntryLogic {
    protected $entry;

    public function __construct(Entry $entry)
    {
        $this->entry = $entry;
    }

    public static function createAdmin($id, $user_id, $path, $comments) {
        $entry = new Entry();
        $entry->project_id = $id;
        $entry->user_id = $user_id;
        $entry->path = $path;
        $entry->admin = true;
        $entry->notes = $comments;
        $entry->save();

        return new EntryLogic($entry);
    }

    public static function createUser($id, $user_id, $path) {
        $entry = new Entry();
        $entry->project_id = $id;
        $entry->user_id = $user_id;
        $entry->path = $path;
        $entry->user_notes = null;
        $entry->notes = null;
        $entry->save();

        return new EntryLogic($entry);
    }

    public function updateEntry($files, $comments) {
        $this->entry->active = true;
        $this->entry->files = json_encode($files);
        $this->entry->notes = $comments;
        $this->entry->save();
    }

    public function get() {
        return $this->entry;
    }



}

?>