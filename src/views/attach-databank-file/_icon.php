<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\documenti\views\documenti
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
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

        <div class="text-truncate">
            <?php echo \yii\helpers\Html::a($model->name, ['/attachments/attach-gallery-image/view', $model->id], [
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
