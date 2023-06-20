<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti
 * @category   CategoryName
 */

use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\PublishedByWidget;
use open20\amos\core\helpers\Html;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\notificationmanager\forms\NewsWidget;
use \open20\amos\attachments\FileModule;
use open20\amos\attachments\assets\ModuleAttachmentsAsset;


ModuleAttachmentsAsset::register($this);
/**
 * @var yii\web\View $this
 * @var \open20\amos\attachments\models\AttachGenericFile $model
 */

$modelViewUrl = $model->getFullViewUrl();
$visible = isset($statsToolbar) ? $statsToolbar : false;
$modelTitleSpecialChars = htmlspecialchars($model->name);
?>

<div class="attachment-databank-item">
    <div class="preview-img-icon">
        <?php if (in_array(strtolower($model->type), ['jpg', 'jpeg', 'png', 'gif'])) : ?>

            <img src="<?= \Yii::$app->params['platform']['frontendUrl'] . $model->getWebUrl() ?>" >

        <?php endif ?>
    </div>
    <?php
    if ($model->isBackendFile()) {
        $realModel = $model->getRealModel();
        echo ContextMenuWidget::widget([
            'model' => $realModel,
            'actionModify' => "/attachments/attach-databank-file/update?id=" . $realModel->id,
            'actionDelete' => "/attachments/attach-databank-file/delete?id=" . $realModel->id,
            'positionAdditionalButton' => 'top',
            'additionalButtons' => [
                empty($hideView) ? Html::a(FileModule::t('amosattachments', 'Anteprima'), ['#', 'id' => $model->id], [
                    'class' => 'open-modal-detail-btn',
                    'data-key' => $model->id,
                ]) : '',
            ],
            'mainDivClasses' => 'manage-attachment'
        ]);
    } else { ?>
        <?php if (\Yii::$app->user->can('ATTACH_DATABANK_FILE_ADMINISTRATOR')) { ?>
            <?=
            ContextMenuWidget::widget([
                'model' => $model,
                'disableModify' => true,
                'disableDelete' => true,
                'additionalButtons' => [
                    empty($hideView) ? Html::a(FileModule::t('amosattachments', 'Anteprima'), ['#', 'id' => $model->id], [
                        'class' => 'open-modal-detail-btn',
                        'data-key' => $model->id,
                    ]) : '',
                    Html::a(FileModule::t('amosattachments', 'Modifica'), ['/attachments/attach-databank-file/convert-file', 'id' => $model->id]),
                    Html::a(FileModule::t('amosattachments', 'Elimina'), ['/attachments/attach-databank-file/delete-cms-file', 'attach_file_id' => $model->id], [
                        'data-confirm' => FileModule::t('amosattachments', "Sei sicuro di eliminare questo elemento?")
                    ])
                ],
                'mainDivClasses' => 'manage-attachment'
            ]); ?>
        <?php } ?>
    <?php } ?>
    <div class="info-item">
        <?php
        if (\Yii::$app->getModule('favorites')) {
            echo \open20\amos\favorites\widgets\SelectFavoriteUrlsWidget::widget([
                'positionRelative' => true,
                'url' => $model->getWebUrl(),
                'module' => 'attachments',
                'controller' => 'view',
                'title' => $model->name,
                'classname' => \open20\amos\attachments\models\AttachDatabankFile::className()
            ]);
        } ?>
    </div>


    <div class="info-attachment">

        <?= $model->getAttachmentIcon(); ?>

        <div>
            <?php echo \yii\helpers\Html::a($model->name, '#', [
                'class' => 'open-modal-detail-btn',
                'data-key' => $model->id,
                'data-toggle' => 'tooltip',
                'title' => FileModule::t('amosdocumenti', 'Vedi dettagli file') . ': ' . $model->name . '.' . $model->type,

            ]); ?>
        </div>

        <div>

            <!-- <span class="text-muted small">< ?= strtoupper($documentMainFile->type); ?>
                (< ?= $documentMainFile->formattedSize ?>) - < ?= AmosDocumenti::tHtml('amosdocumenti', 'File :') ?>
            </span> -->

            <!-- < ?php
            echo Html::tag('span', ((strlen($documentMainFile->name) > 80) ? substr($documentMainFile->name, 0, 75) . '[...]' : $documentMainFile->name) . '.' . $documentMainFile->type, ['class' => 'text-muted small']);
            ? > -->
        </div>

    </div>

</div>