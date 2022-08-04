<?php

namespace open20\amos\attachments\utility;

use open20\amos\attachments\FileModule;
use open20\amos\attachments\interfaces\VirusScanInterface;

class VirusScanUtility
{
    public static function scanFile($path)
    {
        //Let check if we need to scan the file
        $module = FileModule::getInstance();

        if ($module->enableVirusScan) {
            /**
             * @var $scanner VirusScanInterface
             */
            $scanner = \Yii::createObject($module->virusScanClass);

            //Read the VirusScannerInterface Doc for statuses
            return $scanner->scanFile($path);
        }

        return true;
    }
}