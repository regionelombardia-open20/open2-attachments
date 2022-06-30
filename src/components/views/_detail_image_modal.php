<?php

use yii\helpers\Html;
use open20\amos\core\icons\AmosIcons;

/**
 * @var $attribute string
 * @var $model \open20\amos\attachments\models\AttachGalleryImage
 */

use open20\amos\attachments\FileModule;

$fileImage = $model->attachImage;
?>
<div class="row detail-image-masonry">
    <div class="col-sm-7 order-sm-1 text-sm-right">
        <button class="btn btn-xs btn-outline-secondary exit-fullscreen-btn" data-attribute=<?= $attribute ?>>
            <?= FileModule::t('amosattachments', 'Torna alla gallery') ?>
        </button>
        <?= Html::a(FileModule::t('amosattachments', 'Seleziona immagine'), '', [
            'id' => 'image-gallery-link-' . $attribute,
            'class' => 'btn btn-xs btn-primary',
            'title' => FileModule::t('amosattachments', 'Seleziona immagine'),
            'data' => [
                'key' => $model->id,
                'attribute' => $attribute,
                'src' => $model->attachImage->getWebUrl()
            ]
        ]) ?>

        <!-- < ?= Html::a(AmosIcons::show('fullscreen-exit'), '', [
        'class' => 'btn btn-tools-secondary exit-fullscreen-btn',
        'data-attribute' => $attribute
    ]) ?> -->
    </div>
    <div class="col-sm-5 order-sm-0 pt-2">
        <?php
        list($width, $height, $type, $attr) = getimagesize($fileImage->path);
        ?>
        <div class="d-flex flex-wrap">
            <div class="mr-2">
                <strong class="h4"><?= FileModule::t('amosattachments', "Dimensioni (pixel)") . ': ' ?></strong><?= $width . 'x' . $height ?>
            </div>
            <div>
                <strong class="h4"><?= FileModule::t('amosattachments', "Peso") . ': ' ?></strong><?= $fileImage->formattedSize ?>

            </div>
        </div>

    </div>
    <div class="col-12 mt-4 image-detail order-sm-2">
        <?php
        $image = \yii\helpers\Html::img($model->attachImage->getWebUrl(), [
            'class' => 'mx-auto img-responsive'
        ]);
        echo \yii\helpers\Html::a($image, '', [
            'id' => 'exit-fullscreen-btn-' . $attribute,
            'class' => 'exit-fullscreen-btn zoom-out-btn',
            'data' => [
                'key' => $model->id,
                'attribute' => $attribute,
                'src' => $model->attachImage->getWebUrl()
            ]
        ])
        ?>

    </div>
</div>