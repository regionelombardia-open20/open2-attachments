<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-attachments
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

use open20\amos\core\icons\AmosIcons;
use open20\amos\attachments\FileModule;

use open20\amos\attachments\models\Shutterstock;
use \open20\amos\attachments\utility\AttachmentsUtility;
use yii\helpers\Html;

/**
 * @var $attribute string
 * @var $model Shutterstock
 */

$canDownloadImage = Shutterstock::canDownloadImage();
$isInDatabankImages = Shutterstock::isImageInAttachGallery($model->id);
$image = Shutterstock::getRenderedImage($model, 'preview_1500');
$previewUrl = Shutterstock::getUrlImagePreview($model, 'preview_1500');
$categories = Shutterstock::formatCategories($model);
$keywords = Shutterstock::formatKeywords($model);

foreach ($model->assets as $key => $assetsImage) {
    if ($assetsImage->is_licensable) {
        $licensableImages [$key] = $assetsImage;
    }
}


?>

<div class="row detail-image-masonry m-t-20 m-b-20">
    <div class="col-md-8 m-b-20 content-image-modal">
        <?= $image; ?>
    </div>

    <div class="col-md-4">
        <?php
        if (empty($isView)) {
            echo Html::tag('h3', $model->description);
        }
        ?>

        <?php if (!empty($contributorName)) { ?>
            <p><strong><?= FileModule::t('amosattachments', "Autore") . ': ' ?></strong><?= $contributorName ?></p>
        <?php } ?>

        <p><strong><?= FileModule::t('amosattachments', "Formati / Dimensioni immagine") ?></strong></p>

        <?php
        foreach ($licensableImages as $type => $imageSize) {
            if ($type == 'vector_eps') {
                ?>
                <div class="mr-2">
                    <strong class="h4"><?= Shutterstock::labelSizeFormat($model->assets->vector_eps) ?></strong> <?= FileModule::t('amosattachments', "Immagine vettoriale") . ' - formato: ' . $model->assets->vector_eps->format ?>
                </div>
            <?php } else { ?>
                <div class="mr-2">
                    <strong class="h4"><?= Shutterstock::labelSizeFormat($imageSize) ?></strong> <?= $imageSize->width . 'x' . $imageSize->height . ' - ' . $imageSize->dpi . ' - ' . AttachmentsUtility::getFormattedSize($imageSize->file_size) . ' formato: ' . $imageSize->format ?>
                </div>
                <?php
            }
        }
        ?>

        <?php if ($categories) { ?>
            <p class="m-t-30 h4"><?= FileModule::t('amosattachments', "Categorie") ?></p>
            <div class="content-tag">
                <?= $categories ?>
            </div>
        <?php } ?>

        <?php if ($keywords) { ?>
            <p class="m-t-30 h4"><?= FileModule::t('amosattachments', "Parole chiave") ?></p>
            <div class="content-tag">
                <?= $keywords ?>
            </div>
        <?php } ?>

        <div class="m-t-30">
            <?php if (!$isInDatabankImages) { ?>
                <?= FileModule::t('amosattachments', "Seleziona formato da inserire") ?>
                <select id="licenceImageType-id" name="licenceImageType-<?= $attribute ?>" class="form-control">
                    <?php foreach ($licensableImages as $type => $licenseImage) {
                        $value = Shutterstock::licensableImages($type);
                        $selected = '';
                        if ($value == 'huge') {
                            $selected = 'selected';
                        }
                        ?>
                        <option <?= $selected ?>
                                value="<?= $value ?>"><?= Shutterstock::labelSizeFormat($licenseImage, true) ?></option>
                    <?php } ?>
                </select>
                <?php
                if ($canDownloadImage) {
                    $linkOptions = [
                        'class' => 'btn btn-primary upload-from-shutterstock m-t-20',
                        'title' => FileModule::t('amosattachments', 'Inserisci in galleria immagini'),
                        'data-key' => $model->id,
                        'data-src' => Shutterstock::getUrlImagePreview($model, 'preview_1000'),
                        'data-name' => $model->description
                    ];
                } else {
                    $linkOptions = [
                        'class' => 'btn btn-primary m-t-20',
                        'title' => FileModule::t('amosattachments', 'Numero di immagini scaricabili esaurito'),
                        'disabled' => true,
                        'data-toggle' => 'tooltip'
                    ];
                }

                echo Html::a(FileModule::t('amosattachments', 'Inserisci in galleria immagini'),
                    '#',
                    $linkOptions
                );
                ?>
            <?php } else { ?>
                <span class="mdi mdi-image"><?= FileModule::t('amosattachments', "Immagine presente nella galleria immagini") ?></span>
            <?php } ?>
        </div>
    </div>
</div>