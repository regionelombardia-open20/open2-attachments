<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\components\views
 * @category   CategoryName
 */

use open20\amos\attachments\FileModule;
use open20\amos\core\icons\AmosIcons;
use open20\amos\attachments\models\Shutterstock;
use open20\amos\attachments\utility\AttachmentsUtility;

use yii\helpers\Html;

/**
 * @var $attribute string
 * @var $model \open20\amos\attachments\models\AttachGalleryImage
 */
$canDownloadImage = Shutterstock::canDownloadImage();

$licensableImages = [];
$image = Shutterstock::getRenderedImage($model, 'preview_1500');
$isInDatabankImages = Shutterstock::isImageInAttachGallery($model->id);

foreach ($model->assets as $key => $assetsImage) {
    if ($assetsImage->is_licensable) {
        $licensableImages[$key] = $assetsImage;
    }
}
?>
<div class="row detail-shutterstock-masonry">
    <div class="col-md-8 mt-4 content-image-modal order-sm-2">
        <?= Html::a($image, '', [
            'id' => 'exit-fullscreen-btn-shutterstock-' . $attribute,
            'class' => "exit-fullscreen-btn-shutterstock-$attribute zoom-out-btn",
            'data' => [
                'key' => $model->id,
                'attribute' => $attribute,
                'name' => $model->description,
                'src' => Shutterstock::getUrlImagePreview($model, 'preview_1000')
            ]
        ])
        ?>
    </div>


    <div class="col-md-4 m-b-20 order-sm-1 content-image-modal">
        <?php if (!empty($contributorName)) { ?>
            <p><strong><?= FileModule::t('amosattachments', "Autore") . ': ' ?></strong><?= $contributorName ?></p>
        <?php } ?>
        <div>
            <p><strong><?= FileModule::t('amosattachments', "Formato/Dimensione immagine") ?></strong></p>
            <?php
            $image_sizes = ['small_jpg', 'medium_jpg', 'huge_jpg'];
            foreach ($licensableImages as $type => $imageSize) {
                if ($type == 'vector_eps') {
                    ?>
                    <div class="mr-2">
                        <strong><?= Shutterstock::labelSizeFormat($model->assets->vector_eps) ?></strong> <?= FileModule::t('amosattachments', "Immagine vettoriale") . ' - formato: ' . $model->assets->vector_eps->format ?>
                    </div>
                <?php } else { ?>
                    <div class="mr-2">
                        <strong><?= Shutterstock::labelSizeFormat($imageSize) ?></strong> <?= $imageSize->width . 'x' . $imageSize->height . ' - ' . $imageSize->dpi . ' - ' . AttachmentsUtility::getFormattedSize($imageSize->file_size) . ' formato: ' . $imageSize->format ?>
                    </div>
                    <?php
                }
            }
            ?>

            <div class="mr-2 m-t-20">
                <strong><?= FileModule::t('amosattachments', "Tipologia") . ': ' ?></strong><?= Shutterstock::imageTypes()[$model->image_type] ?>
            </div>
            <div>
                <strong><?= FileModule::t('amosattachments', "Categorie") . ': ' ?></strong><?= Shutterstock::formatCategories($model) ?>
            </div>


        </div>
        <?php if (!$isInDatabankImages) { ?>
            <p class="m-t-20"><?= FileModule::t('amosattachments', "Seleziona formato da inserire") ?></p>
            <select id="licenceImageType-id-<?= $attribute ?>" name="licenceImageType-<?= $attribute ?>"
                    class="form-control">
                <?php foreach ($licensableImages as $type => $proscriptionImage) {
                    $value = Shutterstock::licensableImages($type);
                    $selected = '';
                    if ($value == 'huge') {
                        $selected = 'selected';
                    } ?>
                    <option <?= $selected ?>
                            value="<?= $value ?>"><?= Shutterstock::labelSizeFormat($proscriptionImage, true) ?></option>
                <?php } ?>
            </select>
        <?php } else { ?>
            <span class="mdi mdi-image"><?= FileModule::t('amosattachments', "Immagine presente nella galleria immagini") ?></span>
        <?php } ?>
        <div class="m-t-20">
            <button class="btn btn-xs btn-outline-secondary exit-fullscreen-btn-shutterstock-<?= $attribute ?>"
                    data-attribute=<?= $attribute ?>>
                <?= FileModule::t('amosattachments', 'Torna indietro') ?>
            </button>
            <?php
            if ($canDownloadImage) {
                $linkOptions = [
                    'id' => 'image-shutterstock-link-' . $attribute,
                    'class' => 'btn btn-xs btn-primary',
                    'title' => FileModule::t('amosattachments', 'Seleziona immagine'),
                    'data' => [
                        'key' => $model->id,
                        'attribute' => $attribute,
                        'src' => Shutterstock::getUrlImagePreview($model, 'preview_1000'),
                        'name' => $model->description
                    ]
                ];
            } else {
                $linkOptions = [
                    'class' => 'btn btn-xs btn-primary',
                    'title' => FileModule::t('amosattachments', 'Numero di immagini scaricabili esaurito'),
                    'disabled' => true,
                    'data-toggle' => 'tooltip'
                ];
            }
            ?>
            <?= Html::a(FileModule::t('amosattachments', 'Seleziona immagine'), '', $linkOptions) ?>

        </div>
    </div>


    <div class="col-sm-4 order-sm-0 pt-2">

    </div>

</div>