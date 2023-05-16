<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\components\views
 * @category   CategoryName
 */

/**
 * @var $model stdClass
 * @var $attribute string
 */

use open20\amos\core\icons\AmosIcons;
use open20\amos\attachments\FileModule;

use yii\helpers\Html;
use open20\amos\attachments\models\Shutterstock;

$contentImage = \open20\amos\attachments\models\Shutterstock::getRenderedImage($model);
?>

<div class="masonry-item">
    <div class="content-item">
        <?= Html::a(
            $contentImage,
            '',
            [
                'id' => 'img-' . $model->id,
                'class' => 'open-modal-detail-btn open-modal-detail-shutterstock-btn link-shutterstock',
                'title' => FileModule::t('amosattachments', 'Apri immagine') . ' ' . $model->description,
                'data' => [
                    'key' => $model->id,
                    'attribute' => $attribute,
                    'name' => $model->description
                ],
            ]
        ) ?>

       
            <?php if (Shutterstock::isImageInAttachGallery($model->id)) { ?>
                <span data-toggle="tooltip" title="<?=  FileModule::t('amosattachments', "Immagine già presente nella galleria immagini") ?>" class="icon-asset-immagini mdi mdi-image"></span>
            <?php } ?>
       
        <div class="content-action-item">
            <p class="card-title"><?= $model->description ?></p>
        </div>
    </div>
</div>

<div class="clearfix"></div>
