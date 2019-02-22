<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\news
 * @category   CategoryName
 */

namespace lispa\amos\attachments\assets;

use yii\web\AssetBundle;

class ModuleAttachmentsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/lispa/amos-attachments/src/assets/web';

    public $css = [
        'less/attachments.less',
    ];
    public $js = [
    ];
    public $depends = [
    ];

    public function init()
    {
        $moduleL = \Yii::$app->getModule('layout');
        if(!empty($moduleL)){
            $this->depends [] = 'lispa\amos\layout\assets\BaseAsset';
        }else{
            $this->depends [] = 'lispa\amos\core\views\assets\AmosCoreAsset';
        }
        parent::init();
    }
}