<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\attachments
 * @category   CategoryName
 */

namespace lispa\amos\attachments\components;

use lispa\amos\attachments\FileModule;
use Yii;
use yii\base\Component;
use yii\db\ActiveRecord;

/**
 * Class FileImport
 * @package lispa\amos\attachments\components
 */
class FileImport extends Component
{
    /**
     * Import single file on selected Model->attribute
     * @param $modelSpecific ActiveRecord The Record owner of the file
     * @param $attribute string The attribute Name
     * @param $filePath string Path on filesystem
     * @return bool|array File format or Array with error
     */
    public function importFileForModel($modelSpecific, $attribute, $filePath)
    {
        $module = Yii::$app->getModule(FileModule::getModuleName());

        //Se non esiste salto
        if (!file_exists($filePath)) {
            return false;
        }

        $file = [];
        $file['name'] = basename($filePath);
        $file['tempName'] = $filePath;
        $file['type'] = mime_content_type($filePath);
        $file['size'] = filesize($filePath);

        if ($module->attachFile($filePath, $modelSpecific, $attribute)) {
            $result['uploadedFiles'] = [$filePath];
            return $result;
        } else {
            return [
                'error' => $modelSpecific->getErrors(),
                'ioca' => $modelSpecific->getErrors()
            ];
        }
    }
}
