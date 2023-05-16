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

use yii\helpers\Html;

/**
 * @var $attribute string
 * @var $model \open20\amos\attachments\models\AttachGalleryImage
 */
$file = $model->attachmentFile;
?>
<div class="row detail-image-masonry">
    <div class="col-sm-7 order-sm-1 text-sm-right">
        <button class="btn btn-xs btn-outline-secondary exit-fullscreen-btn" data-attribute=<?= $attribute ?>>
            <?= FileModule::t('amosattachments', 'Torna alla gallery') ?>
        </button>
        <?= Html::a(FileModule::t('amosattachments', 'Seleziona file'), '', [
            'id' => 'file-databank-file-link-' . $attribute,
            'class' => 'btn btn-xs btn-primary',
            'title' => FileModule::t('amosattachments', 'Seleziona file'),
            'data' => [
                'key' => $model->id,
                'attribute' => $attribute,
            ]
        ]) ?>
    </div>
    
    <div class="col-sm-5 order-sm-0 pt-2">
        <div class="d-flex flex-wrap">
            <div class="mr-2">
                <strong class="h4"><?= FileModule::t('amosattachments', "Estensione") . ': ' ?></strong><?=$file->type?>
            </div>
            <div>
                <strong class="h4"><?= FileModule::t('amosattachments', "Peso") . ': ' ?></strong><?= $file->formattedSize ?>

            </div>
        </div>

    </div>
</div>