<?php

namespace open20\amos\attachments\bootstrap;

use yii\base\Application;

class StaticFilesManagement implements \yii\base\BootstrapInterface
{
    public static $isStaticFileManagementEnabled = 'attachments_static_files_management_enabled';

    /**
     * @inheritDoc
     */
    public function bootstrap($app)
    {
        \Yii::$app->urlManager->addRules([
                                             'static/<l1:[^\/]+>/<l2:[^\/]+>/<l3:[^\/]+>/<hash:[^\.]+>.<ext:[\d\w]+>' => '/attachments/file/view',
                                         ], false);

        \Yii::$app->params[self::$isStaticFileManagementEnabled] = true;
    }
}