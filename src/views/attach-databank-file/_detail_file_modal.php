<?php

/**
 * @var $model \open20\amos\attachments\models\File
 */

use open20\amos\attachments\FileModule;
use yii\helpers\Html;

$createdAt = $model->getCreatedAt();
$tagsCustomImage = $model->getCustomTagsModel();

?>

<div class="content-modal-databank-file">
    <div class="row">
        <div class="col-lg-2 col-md-4 m-b-20">
            <?= $this->render('_icon', ['model' => $model, 'hideView' => true]) ?>
            <?php      echo Html::a(
                FileModule::t('amosattachment', 'Scarica'),
               $model->getWebUrl(),
                [
                    'title' => FileModule::t('amosattachment', 'Scarica il file'),
                    'class' => 'btn btn-primary-outline m-t-20',
                    'target' => 'blank'
                ]
            );?>
            <?php
            echo Html::a(
                FileModule::t('amosattachments', 'Copia url'),
                \Yii::$app->params['platform']['frontendUrl'].$model->getWebUrl(),
                [
                    'title' => FileModule::t('amosattachment', 'Copia url'),
                    'class' => 'btn btn-primary-outline m-t-20 copy-and-paste',
                    'target' => 'blank'
                ]
            );?>
        </div>
        <div class="col-lg-10 col-md-8">
            <div class="detail-attachment-modal">
                <p>
                    <strong><?= FileModule::t('amosattachments', "Caricato il") . ': ' ?></strong>
                    <?= $createdAt->format('d/m/Y') . ' alle ' . $createdAt->format('H:i') ?>
                    <?= FileModule::t('amosattachments', "da") ?> <?= $model->getCreatedByProfile() ?>
                </p>
                <p>
                    <strong><?= FileModule::t('amosattachments', "Nome file") . ': ' ?></strong><?= $model->name ?>
                </p>

                <p>
                    <strong>
                        <?= FileModule::t('amosattachments', "Estensione")
                        . ': ' ?>
                    </strong>
                    <?= $model->type ?>
                </p>

                <p>
                    <strong>
                        <?= FileModule::t('amosattachments', "Dimensione")
                        . ': ' ?>
                    </strong>
                    <?= $model->formattedSize ?>
                </p>

                <p>
                    <strong><?= FileModule::t('amosattachments', "Url file") ?></strong>
                    <?= \Yii::$app->params['platform']['frontendUrl'].$model->getWebUrl() ?>
                </p>
                <?php if (!empty($tagsCustomImage)) { ?>
                    <br>
                    <p>
                        <strong><?= FileModule::t('amosattachments', "Tag liberi") ?></strong>
                    <div class="content-tag">
                        <?= \open20\amos\attachments\utility\AttachmentsUtility::formatTags($tagsCustomImage) ?>
                    </div>
                    </p>
                <?php } ?>
            </div>
        </div>
    </div>