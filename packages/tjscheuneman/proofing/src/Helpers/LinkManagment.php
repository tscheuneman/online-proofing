<?php

namespace Tjscheuneman\Proofing\Helpers;

use File;
use Storage;

use Auth;


class LinkManagment
{
    /**
     * Get the dropbox link given the path
     *
     * $param string $val
     * @return string
     */
    public static function getDropboxLink($val) {
        return Storage::disk('dropbox')->getAdapter()->getTemporaryLink($val);
    }

}

