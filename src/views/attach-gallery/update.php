<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-attachments/src/views
 */

use open20\amos\attachments\FileModule;

/**
 * @var yii\web\View $this
 * @var open20\amos\attachments\models\AttachGallery $model
 */
$module = \Yii::$app->getModule('attachments');
$enableSingleGallery = $module->enableSingleGallery;

if ($enableSingleGallery) {
    $this->title = FileModule::t('amosattachments', 'Images databank');
} else{
    $this->title = FileModule::t('amosattachments', 'Images databank')
        . " '"
        . $model->name
        . "'";
}

$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/attachments']];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('amoscore', 'Attach Gallery'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->title;


?>

<div class="attach-gallery-update">
    <?= $this->render('../attach-gallery-image/_search', [
        'model' => $modelSearch,
        'originAction' => Yii::$app->controller->action->id
        ]);
    ?>

    <?= $this->render('_form', [
        'model' => $model,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
        'dataProviderImages' => $dataProviderImages,
        'currentView' => $currentView
    ]) ?>
</div>
