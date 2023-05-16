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
$url = $model->getWebUrl('item_news', false, true);
$realModel = $model->getRealModel();


$contentImage = Html::img(
    $url,
    [
        'class' => 'full-width',
        'alt' => FileModule::t('amosattachments', "Immagine " . $model->getGenericName())
    ]
);

if (empty($urlPlaceholder)) {
    $urlPlaceholder = '/img/img_default.jpg';
}
?>

<div class="masonry-item">
    <div class="content-item" tabindex="0">
        <?= Html::a($contentImage, $model->getFullViewUrl(), [
            'class' => 'open-modal-detail-btn',
            'data-key' => $model->id,
            'title' => FileModule::t('amosattachments', 'Apri immagine' . ' ' . $model->getGenericName()),
            'style' => 'display:none'
        ]) ?>
        <?php echo Html::img($urlPlaceholder, [
            'class' => 'placeholder-image full-width',
            'title' => FileModule::t('amosattachments', 'Apri immagine' . ' ' . $model->getGenericName()),
        ]) ?>
        <div class="info-item">
            <?php if (\Yii::$app->getModule('favorites') && class_exists('open20\amos\favorites\widgets\SelectFavoriteUrlsWidget')) {
                echo \open20\amos\favorites\widgets\SelectFavoriteUrlsWidget::widget([
                    'positionRelative' => true,
                    'url' => $url,
                    'module' => 'attachments',
                    'controller' => 'view',
                    'title' => $model->getGenericName(),
                    'classname' => \open20\amos\attachments\models\AttachGalleryImage::className()
                ]);
            } ?>
            <?php if ($model->isBackendFile() && $model->isFromShutterstock()) { ?>
                <span class="mdi mdi-web icon-file-cms" tabindex="0" data-toggle="tooltip" data-placement="bottom" title="<?= \Yii::t('amosattachments', 'Immagine proveniente da libreria Shutterstock') ?>"></span>
            <?php } ?>
        </div>

        <div class="action-item">
            <?php if ($model->isBackendFile()) { ?>
                <?= NewsWidget::widget(['model' => $realModel]); ?>
                <?= ContextMenuWidget::widget([
                    'model' => $realModel,
                    'actionModify' => "/attachments/attach-gallery-image/update?id=" . $realModel->id,
                    'actionDelete' => "/attachments/attach-gallery-image/delete?id=" . $realModel->id,
                    'labelDeleteConfirm' => AmosNews::t('amosnews', 'Sei sicuro di voler cancellare questa immagine?'),
                    'positionAdditionalButton' => 'top',
                    'additionalButtons' => [
                        Html::a(FileModule::t('amosattachments','Anteprima'), ['#', 'id' => $model->id],[
                            'class' => 'open-modal-detail-btn',
                            'data-key' => $model->id,
                        ]),
                    ],
                ]) ?>
            <?php } else { ?>
                <?php if (\Yii::$app->user->can('MANAGE_ATTACH_GALLERY')) { ?>
                    <?=
                    ContextMenuWidget::widget([
                        'model' => $model,
                        'disableModify' => true,
                        'disableDelete' => true,
                        'additionalButtons' => [
                            Html::a(FileModule::t('amosattachments','Anteprima'), ['#', 'id' => $model->id],[
                                'class' => 'open-modal-detail-btn',
                                'data-key' => $model->id,
                            ]),
                            Html::a(FileModule::t('amosattachments', 'Modifica'), ['/attachments/attach-gallery-image/convert-file', 'id' => $model->id]),
                            Html::a(FileModule::t('amosattachments', 'Elimina'), ['/attachments/attach-gallery-image/delete-cms-file', 'attach_file_id' => $model->id], [
                                'data-confirm' => FileModule::t('amosattachments', "Sei sicuro di eliminare questa immagine?"),
                                'style' => 'border-top: 1px solid #ccc;color:#a61919;'
                            ])
                        ],
                        'mainDivClasses' => 'manage-attachment'
                    ]); ?>
                <?php } ?>
            <?php } ?>


        </div>

        <div class="content-action-item">
            <?= Html::a(
                Html::tag(
                    'p',
                    $model->getGenericName(),
                    [
                        'class' => 'card-title'
                    ]
                ),
                $model->getFullLinkViewUrl(),
                [
                    'class' => 'link-list-title',
                    'title' => "Vai alla scheda immagine " . $model->getGenericName(),
                ]
            )
            ?>
        </div>
    </div>
</div>
<div class="clearfix"></div>