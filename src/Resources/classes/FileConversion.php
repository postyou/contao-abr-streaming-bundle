<?php

namespace Postyou\ContaoABRStreamingBundle;

use Contao\PageModel;
use Contao\FilesModel;
use Contao\System;
use Contao\File;

class FileConversion
{
    public function addPreviewImage($row, $label)
    {
        if ($row['videoFile'] != '')
        {
            $videoFile = FilesModel::findByUuid($row['videoFile']);

            if ($videoFile !== null)
            {
                dump($videoFile);
            }
        }
        return $label;
    }
}