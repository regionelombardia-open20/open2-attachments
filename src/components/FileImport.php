<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\attachments
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
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
