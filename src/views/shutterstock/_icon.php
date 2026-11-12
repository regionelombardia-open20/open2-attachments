<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\news\views\news
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
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\helpers\Html;
use open20\amos\news\AmosNews;
use open20\amos\notificationmanager\forms\NewsWidget;
use open20\amos\core\utilities\CurrentUser;
use open20\amos\attachments\models\Shutterstock;

/**
 * @var \open20\amos\attachments\models\AttachGalleryImage $model
 */


$contentImage = Shutterstock::getRenderedImage($model);

?>

<div class="masonry-item">
    <div class="content-item" tabindex="0">
        <?= Html::a($contentImage, '#', [
            'class' => 'open-modal-detail-btn open-modal-detail-shutterstock-btn',
            'data-key' => $model->id,
            'title' => FileModule::t('amosattachments', 'Apri immagine' . ' ' . $model->description),
        ]) ?>



            <?php if (Shutterstock::isImageInAttachGallery($model->id)) { ?>
                <span data-toggle="tooltip" title="<?=  FileModule::t('amosattachments', "Immagine già presente nella galleria immagini") ?>" class="icon-asset-immagini mdi mdi-image"></span>
            <?php } ?>
<!--                --><?php //ContextMenuWidget::widget([
//                    'model' => $realModel,
//                    'actionModify' => "/attachments/attach-gallery-image/update?id=" . $realModel->id,
//                    'actionDelete' => "/attachments/attach-gallery-image/delete?id=" . $realModel->id,
//                    'labelDeleteConfirm' => AmosNews::t('amosnews', 'Sei sicuro di voler cancellare questa immagine?'),
//                ]) ?>

    

        <div class="content-action-item">
            <?= Html::a(
                Html::tag(
                    'p',
                    $model->description,
                    [
                        'class' => 'card-title'
                    ]
                ),
                '#',
                [
                    'class' => 'link-list-title',
                    'title' => "Vai alla scheda immagine " . $model->description,
                ]
            )
            ?>
        </div>
    </div>
</div>
<div class="clearfix"></div>