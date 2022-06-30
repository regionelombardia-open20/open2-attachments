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
 * @var open20\amos\attachments\models\AttachGallery $model
 */

$this->title = \open20\amos\attachments\FileModule::t('amosattachments', 'Update gallery')." '". $model->name."'";
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/attachments']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Attach Gallery'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attach-gallery-update">

    <?= $this->render('_form', [
        'model' => $model,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
        'dataProviderImages' => $dataProviderImages
    ]) ?>

</div>
