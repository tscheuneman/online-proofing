<?php

namespace Tjscheuneman\Proofing\Helpers;

use App\Entry;

class EntryLogic {
    protected $entry;

    /**
     * EntryLogic constructor
     *
     * @param  \App\Entry $entry
     * @return void
     */
    public function __construct(Entry $entry)
    {
        $this->entry = $entry;
    }

    /**
     * Create an Admin Entry
     *
     * @param  string $id
     * @param string $user_id
     * @param string $path
     * @param string $comments
     * @return EntryLogic
     */
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

    /**
     * Create a User Entry
     *
     * @param  string $id
     * @param string $user_id
     * @param string $path
     * @return EntryLogic
     */
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

    /**
     * Update an entry
     *
     * @param array $files
     * @param string $comments
     * @return void
     */
    public function updateEntry($files, $comments) {
        $this->entry->active = true;
        $this->entry->files = json_encode($files);
        $this->entry->notes = $comments;
        $this->entry->save();
    }

    /**
     * Get the current entry
     *
     * @return \App\Entry
     */
    public function get() {
        return $this->entry;
    }



}

?>