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

/**
 * @var \open20\amos\attachments\models\AttachGalleryImage $model
 */
$url = '/img/img_default.jpg';
if (!is_null($model->attachImage)) {
    $url = $model->attachImage->getUrl('item_news', false, true);
}

$contentImage = Html::img(
    $url,
    [
        'class' => 'full-width',
        'alt' => FileModule::t('amosattachments', "Immagine " . $model->name)
    ]
);
?>

<div class="masonry-item">
    <div class="content-item">
        <?= Html::a($contentImage, $model->getFullViewUrl(), [
            'title' => "Apri immagine " . $model->name,
            'class' => 'open-modal-detail-btn',
            'data-key' => $model->id
        ]) ?>
        
        <div class="info-item">
        <?= NewsWidget::widget(['model' => $model]); ?>
        
        <?= ContextMenuWidget::widget([
            'model' => $model,
            'actionModify' => "/attachments/attach-gallery-image/update?id=" . $model->id,
            'actionDelete' => "/attachments/attach-gallery-image/delete?id=" . $model->id,
            'labelDeleteConfirm' => AmosNews::t('amosnews', 'Sei sicuro di voler cancellare questa immagine?'),
        ]) ?>
        </div>
          
        <div class="content-action-item">
        <?= Html::a(
            Html::tag(
                'p',
                $model->name,
                [
                    'class' => 'card-title'
                ]
            ),
            $model->getFullViewUrl(),
            [
                'class' => 'link-list-title',
                'title' => "Vai alla scheda immagine " . $model->name
            ]
        )
        ?>
        </div>
    </div>
</div>
<div class="clearfix"></div>
