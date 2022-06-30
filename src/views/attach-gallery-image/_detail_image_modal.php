<?php

use yii\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\attachments\FileModule;

$fileImage = $model->attachImage;

/**
 * @var $attribute string
 * @var $model \open20\amos\attachments\models\AttachGalleryImage
 */

?>

<div class="row detail-image-masonry m-t-20 m-b-20">
    <div class="col-sm-8 image-detail">
        <?php
        $image = \yii\helpers\Html::img($model->attachImage->getWebUrl(), [
            'class' => 'img-responsive'
        ]);
        echo $image;
        ?>
    </div>


    <div class="col-sm-4">
        <?php if (empty($isView)) { ?>
            <?= Html::tag('h3', $model->name); ?>
        <?php } ?>
        <p>
            <strong class="h4"><?= FileModule::t('amosattachments', "Published by") . ': ' ?></strong><?= $model->getCreatedByProfile() ?>
            <br>
            <strong class="h4"><?= FileModule::t('amosattachments', "at") . ': ' ?></strong><?= \Yii::$app->formatter->asDate($model->created_at) ?>
        </p>
        <p>
            <?php
            list($width, $height, $type, $attr) = getimagesize($fileImage->path);
            ?>
            <strong class="h4"><?= FileModule::t('amosattachments', "Dimensioni (pixel)") . ': ' ?></strong><?= $width . 'x' . $height ?>
            <br>
            <strong class="h4"><?= FileModule::t('amosattachments', "Aspect ratio") . ': ' ?></strong><?= \open20\amos\attachments\utility\AttachmentsUtility::getFormattedAspectRatio($model->aspect_ratio) ?>
            <br>
            <strong class="h4"><?= FileModule::t('amosattachments', "Estensione") . ': ' ?></strong><?= $fileImage->type ?>
            <br>
            <strong class="h4"><?= FileModule::t('amosattachments', "Peso") . ': ' ?></strong><?= $fileImage->formattedSize ?>
            <br>

        </p>

        <p>
        <h4><?= FileModule::t('amosattachments', "Tag di interesse informativo") ?></h4>
        <div class="content-tag">
            <?php $tagsImage = $model->getTagsImageModel();
            foreach ($tagsImage as $tagImage) {
                echo Html::a($tagImage->nome . ' ', ['/attachments/attach-gallery/single-gallery?AttachGalleryImageSearch[tagsImageSearch]=' . $tagImage->id], ['class' => 'label label-default']);
            } ?>
        </div>
        </p>

        <p>
        <h4><?= FileModule::t('amosattachments', "Tag liberi") ?></h4>
        <div class="content-tag">
            <?php $tagsImage = $model->getCustomTagsModel();
            foreach ($tagsImage as $tagImage) {
                echo Html::a($tagImage->nome . ' ', ['/attachments/attach-gallery/single-gallery?AttachGalleryImageSearch[customTagsSearch]=' . $tagImage->nome], ['class' => 'label label-default']);
            } ?>
        </div>
        </p>


        <div class="m-t-30">
            <?php if (\Yii::$app->user->can('MANAGE_ATTACH_GALLERY')) { ?>
                <?= Html::a(FileModule::t('amosattachments', 'Download'), [$model->attachImage->getWebUrl()], [
                    'class' => 'btn btn-primary',
                    'title' => FileModule::t('amosattachments', 'Download'),
                ]) ?>
            <?php } ?>
            <?php if (\Yii::$app->user->can('ATTACHGALLERYIMAGE_UPDATE', ['model' => $model])) { ?>
                <?= Html::a(FileModule::t('amosattachments', 'Modifica'), ['/attachments/attach-gallery-image/update', 'id' => $model->id], [
                    'class' => 'btn btn-secondary',
                    'title' => FileModule::t('amosattachments', 'Modifica'),

                ]) ?>
            <?php } ?>
            <?php if (\Yii::$app->user->can('ATTACHGALLERYIMAGE_DELETE', ['model' => $model])) { ?>
                <?= Html::a(FileModule::t('amosattachments', 'Elimina'), ['/attachments/attach-gallery-image/delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data-confirm' => FileModule::t('amosattachments', 'Sei sicuro di cancellare questa immagine?'),
                    'title' => FileModule::t('amosattachments', 'Elimina'),

                ]) ?>
            <?php } ?>
        </div>


    </div>