<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\components\views
 * @category   CategoryName
 */

use open20\amos\core\icons\AmosIcons;
use open20\amos\attachments\FileModule;

use yii\helpers\Html;

$src = '/img/img_default.jpg';
if($model->attachImage){
    $src = $model->attachImage->getWebUrl();

}
?>
<div class="masonry-item">
    <div class="content-item">
        <?= Html::a(
            Html::img(!empty($model->attachImage) ? $model->attachImage->getWebUrl('item_news') : '', ['class' => 'img-responsive']),
            '',
            [
                'id' => 'img-' . $model->id,
                'class' => 'open-modal-detail-btn link-image',
                'title' => FileModule::t('amosattachments', 'Apri immagine'),
                'data' => [
                    'key' => $model->id,
                    'attribute' => $attribute
                ]
            ]
        ) ?>
        <div class="info-item">
        <?= \yii\helpers\Html::a(AmosIcons::show('plus', ['class' => 'am']), '', [
                'class' => 'select-image-'.$attribute.' btn btn-xs btn-white py-1 px-2',
                'title' => FileModule::t('amosattachments', 'Seleziona immagine'),
                'data' => [
                    'key' => $model->id,
                    'attribute' => $attribute,
                    'src' => $src
                ]
            ]
        );
        ?>
        </div>
        
        <div class="content-action-item">
            <p class="card-title"><?= $model->name ?></p>
        </div>
    </div>
</div>

<div class="clearfix"></div>