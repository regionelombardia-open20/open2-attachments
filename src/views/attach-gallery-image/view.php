<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-attachments/src/views
 */

use open20\amos\attachments\FileModule;
use open20\amos\attachments\assets\ModuleAttachmentsAsset;

use kartik\datecontrol\DateControl;

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

ModuleAttachmentsAsset::register($this);

/**
 * @var yii\web\View $this
 * @var open20\amos\attachments\models\AttachGalleryImage $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/attachments/attach-gallery/single-gallery']];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('amoscore', 'Attach Gallery Image'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->title;

echo $this->render('_detail_image_modal', [
    'model' => $model,
    'isView' => true
])
?>

<div id="form-actions" class="col-xs-12 m-t-30">
<?= Html::a(Yii::t('amoscore', 'Chiudi'),
    \Yii::$app->request->referrer,
    ['class' => 'btn btn-secondary pull-right']
);
?>
</div>
