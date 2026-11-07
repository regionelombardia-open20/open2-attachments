<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments
 * @category   CategoryName
 */

namespace open20\amos\attachments\components;

use yii\web\UploadedFile;

class CustomUploadFile extends UploadedFile {
    /**
     * @inheridoc
     */
    public function saveAs($file, $deleteTempFile = true)
    {
        return rename($this->tempName, $file);
    }
}