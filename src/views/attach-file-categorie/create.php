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
$this->title = FileModule::t('amosattachments', 'Crea', [
    'modelClass' => 'Attach File Categorie',
]);
//$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/attachments']];
$this->params['breadcrumbs'][] = ['label' => FileModule::t('amosattachments',
    'Categorie Allegati'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attach-file-categorie-create">
    <?= $this->render('_form', [
        'model' => $model,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
    ]) ?>
</div>
