<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-attachments/src/views
 */
/**
 * @var yii\web\View $this
 * @var open20\amos\attachments\models\AttachGalleryRequest $model
 */

$module = \Yii::$app->getModule('attachments');
$enableSingleGallery = $module->enableSingleGallery;
if($enableSingleGallery){
    $this->title = \open20\amos\attachments\FileModule::t('amosattachments', 'Richiedi immagine');
}
else{
    $this->title = \open20\amos\attachments\FileModule::t('amosattachments', 'Richiedi immagine per databank')." '". $model->name."'";
}
$this->title = Yii::t('amoscore', 'Richiedi immagine');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/attachments']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Attach Gallery Request'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attach-gallery-request-create">
    <?= $this->render('_form', [
        'model' => $model,
        'gallery' => $gallery,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
    ]) ?>

</div>
