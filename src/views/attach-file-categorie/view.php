<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos/attachments/views 
 */
use open20\amos\attachments\FileModule;
use open20\amos\core\module\BaseAmosModule;
//use kartik\datecontrol\DateControl;
use yii\widgets\DetailView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var open20\amos\attachments\models\AttachFileCategorie $model */
$this->title = strip_tags($model->titolo);
//$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/attachments']];
$this->params['breadcrumbs'][] = ['label' => FileModule::t('amosattachments',
    'Categorie Allegati'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attach-file-categorie-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'titolo',
            [
                'label' => $model->getAttributeLabel('assets'),
                'format' => 'html',
                'value' => function ($model) {
                    /** @var AttachFileCategorieSearch $model */
                    $assetNames = $model->getAssetNames();
                    if (empty($assetNames)) {
                        return null;
                    }
                    $res = '<ul>' . PHP_EOL;
                    foreach ($assetNames as $assetName) {
                        $res .= '<li>' . $assetName . '</li>';
                    }
                    $res .= '</ul>' . PHP_EOL;
                    return $res;
                }
            ],
        ],
    ]) ?>
</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(BaseAmosModule::t('amosattachments', 'Chiudi'),
        Url::previous(), ['class' => 'btn btn-secondary']); ?>
</div>
