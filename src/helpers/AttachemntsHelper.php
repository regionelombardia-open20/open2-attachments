<?php

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