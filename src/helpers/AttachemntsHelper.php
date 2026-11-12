<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-attachments
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

namespace open20\amos\attachments\helpers;

use open20\amos\attachments\bootstrap\StaticFilesManagement;
use open20\amos\attachments\FileModule;
use yii\helpers\FileHelper;

class AttachemntsHelper
{

    public static function getPathByHash($hash, $static = false)
    {
        $fileModule = FileModule::getInstance();

        //The main store path
        $storePath = \Yii::getAlias($fileModule->storePath);

        if ($static && self::getIsStaticServed()) {
            //The Static Public Storage Path
            $staticStorePath = \Yii::getAlias('@webroot/static');

            $path = $staticStorePath . DIRECTORY_SEPARATOR . self::getSubDirs($hash);
        } else {
            $path = $storePath . DIRECTORY_SEPARATOR . self::getSubDirs($hash);
        }

        //Create if not exists
        FileHelper::createDirectory($path, 0777);

        return $path;
    }

    /**
     * @param $fileHash
     * @param int $depth
     * @return string
     */
    public static function getSubDirs($fileHash, $depth = 3)
    {
        $depth = min($depth, 9);
        $path = '';

        for ($i = 0; $i < $depth; $i++) {
            $folder = substr($fileHash, $i * 3, 2);
            $path .= $folder;
            if ($i != $depth - 1) {
                $path .= DIRECTORY_SEPARATOR;
            }
        }

        return $path;
    }

    /**
     * Need to Manage static served attachments?
     * @return bool
     */
    public static function getIsStaticServed() {
        return isset(\Yii::$app->params[StaticFilesManagement::$isStaticFileManagementEnabled]) && \Yii::$app->params[StaticFilesManagement::$isStaticFileManagementEnabled];
    }
}