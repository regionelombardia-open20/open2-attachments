<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\views\news
 * @category   CategoryName
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