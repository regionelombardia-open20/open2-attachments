<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

namespace open20\amos\attachments\assets;

use yii\web\AssetBundle;

class CropperAsset extends AssetBundle
{
    public $sourcePath = '@vendor/bower-asset/jquery-cropper/dist';

    public $css = [
       // 'less/attachments.less',
    ];
    public $js = [
        'jquery-cropper.min.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];

    public function init()
    {
        $moduleL = \Yii::$app->getModule('layout');

        if(!empty($moduleL)){
            $this->depends [] = 'open20\amos\layout\assets\BaseAsset';
        }else{
            $this->depends [] = 'open20\amos\core\views\assets\AmosCoreAsset';
        }

        parent::init();
    }
}