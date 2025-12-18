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
<?php } ?>