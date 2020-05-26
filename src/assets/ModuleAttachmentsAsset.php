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
use open20\amos\core\widget\WidgetAbstract;

class ModuleAttachmentsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/open20/amos-attachments/src/assets/web';

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

        if(!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS){
            $this->css = ['less/attachments_fullsize.less'];
        }

        if(!empty($moduleL)){
            $this->depends [] = 'open20\amos\layout\assets\BaseAsset';
        }else{
            $this->depends [] = 'open20\amos\core\views\assets\AmosCoreAsset';
        }
        parent::init();
    }
}