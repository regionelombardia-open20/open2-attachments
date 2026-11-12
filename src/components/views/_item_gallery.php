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

/**
 * @var $model \open20\amos\attachments\models\AttachGenericImage
 */

use open20\amos\core\icons\AmosIcons;
use open20\amos\attachments\FileModule;

use yii\helpers\Html;

$src = '/img/img_default.jpg';
$filePath = $model->getPath();

if (file_exists($filePath)) {
    $src = $model->getWebUrl();

$iconSelected = 'checkbox-blank-outline';
    
?>
    <div class="masonry-item">
        <div class="content-item">
            <?= Html::a(
                Html::img(!empty($model) ? $model->getWebUrl('item_news') : '', ['class' => 'img-responsive']),
                '',
                [
                    'id' => 'img-' . $model->id,
                    'class' => 'open-modal-detail-btn link-image',
                    'title' => FileModule::t('amosattachments', 'Apri immagine' . ' ' . $model->getGenericName()),
                    'data' => [
                        'key' => $model->id,
                        'attribute' => $attribute,
                    ],
                    'style' => 'display:none'
                ]
            ) ?>
            <?= Html::img($urlPlaceholder, [
                'class' => 'placeholder-image full-width',
                'title' => FileModule::t('amosattachments', 'Apri immagine' . ' ' . $model->getGenericName()),
            ]) ?>
            <div class="info-item">
                <?php if ($model->isBackendFile() && $model->isFromShutterstock()): ?>
                    <span class="mdi mdi-web icon-file-cms" tabindex="0" data-toggle="tooltip" data-placement="bottom" title="<?= \Yii::t('amosattachments', 'Immagine proveniente da libreria Shutterstock') ?>"></span>
                <?php endif; ?>
            </div>

            <div class="action-item">
                <?php if ($multiSelection): ?>
                
                    <div class="multi-selection-item">
                        <span data-name="<?= $model->name . '.' . $model->type ?>" data-key="<?= $model->id ?>" class="select-multimage-<?= $attribute ?> checkbox-selection mdi mdi-<?= $iconSelected ?> icon-file-check mdi-18px btn"></span>
                    </div>
                
                <?php else: ?>
                <?= \yii\helpers\Html::a(
                    AmosIcons::show('plus', ['class' => 'am']),
                    '',
                    [
                        'class' => 'select-image-' . $attribute . ' btn btn-xs btn-white py-1 px-2',
                        'title' => FileModule::t('amosattachments', 'Seleziona immagine'),
                        'data' => [
                            'key' => $model->id,
                            'attribute' => $attribute,
                            'src' => $src,
                            'filename' => $model->name . '.' . $model->type
                        ]
                    ]
                );
                ?>
                <?php endif; ?>
            </div>

            <div class="content-action-item">
                <p class="card-title"><?= $model->getGenericName() ?></p>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
<?php } else { ?>
    <div class="masonry-item no-image-avaible">
        <div class="content-item">
            <span class="icon icon-image icon-sm am am-block"></span>
            Immagine non disponibile
        </div>
    </div>
<?php } ?>
