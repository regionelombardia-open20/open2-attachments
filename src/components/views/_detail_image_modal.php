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

use yii\helpers\Html;

/**
 * @var $attribute string
 * @var $model \open20\amos\attachments\models\AttachGalleryImage
 */
list($width, $height, $type, $attr) = getimagesize($model->path);
$image = Html::img($model->getWebUrl(), [
    'class' => 'mx-auto img-responsive'
]);
?>
<div class="row detail-image-masonry">
    <div class="col-sm-7 order-sm-1 text-sm-right">
        <button class="btn btn-xs btn-outline-secondary exit-fullscreen-btn" data-attribute=<?= $attribute ?>>
            <?= FileModule::t('amosattachments', 'Torna alla gallery') ?>
        </button>
        <?php if (!$multiSelection): ?>
            <?= Html::a(FileModule::t('amosattachments', 'Seleziona immagine'), '', [
                'id' => 'image-gallery-link-' . $attribute,
                'class' => 'btn btn-xs btn-primary',
                'title' => FileModule::t('amosattachments', 'Seleziona immagine'),
                'data' => [
                    'key' => $model->id,
                    'attribute' => $attribute,
                    'src' => $model->getWebUrl(),
                    'filename' => $model->name.'.'.$model->type
                ]
            ]) ?>
        <?php endif; ?>
    </div>
    
    <div class="col-sm-5 order-sm-0 pt-2">
        <div class="d-flex flex-wrap">
            <div class="mr-2">
                <strong class="h4"><?= FileModule::t('amosattachments', "Dimensioni (pixel)") . ': ' ?></strong><?= $width . 'x' . $height ?>
            </div>
            <div>
                <strong class="h4"><?= FileModule::t('amosattachments', "Peso") . ': ' ?></strong><?= $model->formattedSize ?>

            </div>
        </div>

    </div>
    <div class="col-12 mt-4 image-detail order-sm-2">
    <?= Html::a($image, '', [
            'id' => 'exit-fullscreen-btn-' . $attribute,
            'class' => 'exit-fullscreen-btn zoom-out-btn',
            'data' => [
                'key' => $model->id,
                'attribute' => $attribute,
                'src' => $model->getWebUrl()
            ]
        ])
        ?>
    </div>
</div>