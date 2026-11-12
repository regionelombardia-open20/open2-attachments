<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\attachments\components\views
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
                <?php foreach ($licensableImages as $type => $licenseImage) {
                    $value = Shutterstock::licensableImages($type);
                    $selected = '';
                    if ($value == 'huge') {
                        $selected = 'selected';
                    } ?>
                    <option <?= $selected ?>
                            value="<?= $value ?>"><?= Shutterstock::labelSizeFormat($licenseImage, true) ?></option>
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