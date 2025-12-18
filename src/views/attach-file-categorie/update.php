<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos/attachments/views 
 */

use open20\amos\attachments\FileModule;

/**
 * @var yii\web\View $this
 * @var open20\amos\attachments\models\AttachFileCategorie $model
 */
$this->title = FileModule::t('amosattachments', 'Aggiorna', [
    'modelClass' => 'Attach File Categorie',
]);
//$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/attachments']];
$this->params['breadcrumbs'][] = ['label' => FileModule::t('amosattachments',
    'Categorie Allegati'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = FileModule::t('amosattachments', 'Aggiorna');
?>
<div class="attach-file-categorie-update">
    <?= $this->render('_form', [
        'model' => $model,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
    ]) ?>
</div>
