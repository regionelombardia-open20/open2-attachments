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

use open20\amos\attachments\FileModule;
use Yii;
use yii\base\Component;
use yii\db\ActiveRecord;

/**
 * Class FileImport
 * @package open20\amos\attachments\components
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
    public function importFileForModel($modelSpecific, $attribute, $filePath, $dropOriginFile = true)
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

        if ($module->attachFile($filePath, $modelSpecific, $attribute,$dropOriginFile)) {
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
