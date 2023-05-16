<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-attachments/src/views
 */

use open20\amos\core\icons\AmosIcons;
use open20\amos\attachments\FileModule;
use \open20\amos\attachments\utility\AttachmentsUtility;
use open20\amos\core\forms\ContextMenuWidget;
use yii\helpers\Html;

/**
 * @var $attribute string
 * @var $model \open20\amos\attachments\models\AttachGalleryImage
 */


$image = Html::img($model->getWebUrl(), [
    'class' => 'img-responsive'
]);

list($width, $height, $type, $attr) = getimagesize($model->path);

$tagsImage = $model->getTagsImageModel();
$tagsImage = [];
$tagsCustomImage = $model->getCustomTagsModel();
$realModel = $model->getRealModel();
$createdAt = $model->getCreatedAt();

?>

<div class="row detail-image-masonry m-t-20 m-b-20">
    <div class="col-sm-8 m-b-20">
        <?= $image; ?>
    </div>

    <div class="col-sm-4">
        <?php
        if (empty($isView)) {
            echo Html::tag('h3', $model->getGenericName());
        }
        ?>
        <?php if ($model->isBackendFile()) { ?>
            <?= ContextMenuWidget::widget([
                'model' => $realModel,
                'actionModify' => "/attachments/attach-gallery-image/update?id=" . $realModel->id,
                'actionDelete' => "/attachments/attach-gallery-image/delete?id=" . $realModel->id,
                'labelDeleteConfirm' => FileModule::t('amosnews', 'Sei sicuro di voler cancellare questa immagine?'),
            ]) ?>
        <?php } else { ?>
            <?php if (\Yii::$app->user->can('MANAGE_ATTACH_GALLERY')) { ?>
                <?=
                ContextMenuWidget::widget([
                    'model' => $model,
                    'disableModify' => true,
                    'disableDelete' => true,
                    'additionalButtons' => [
                        Html::a(FileModule::t('amosattachments','Modifica'), ['/attachments/attach-gallery-image/convert-file', 'id' => $model->id]),
                        Html::a(FileModule::t('amosattachments','Elimina'), ['/attachments/attach-gallery-image/delete-cms-file', 'attach_file_id' => $model->id],[
                                'data-confirm' => FileModule::t('amosattachments',"Sei sicuro di cancellare questa immagine? {title}",['title' => $model->title])
                        ])
                    ],
                    'mainDivClasses' => 'manage-attachment'
                ]); ?>
                
            <?php }
        }?>
        <p>
            <strong class="h4">
                <?= FileModule::t('amosattachments', "Published by")
                . ': ' ?>
            </strong>
            <?= $model->getCreatedByProfile() ?>
            <br/>
            <strong class="h4">
                <?= FileModule::t('amosattachments', "at")
                . ': ' ?>
            </strong>
            <?= $createdAt->format('d/m/Y') ?>
        </p>

        <p>
            <strong class="h4">
                <?= FileModule::t('amosattachments', "Dimensioni (pixel)")
                . ': ' ?>
            </strong>
            <?= $width . 'x' . $height ?>

            <?php if ($realModel) { ?>
                <br/>

                <strong class="h4">
                    <?= FileModule::t('amosattachments', "Aspect ratio")
                    . ': ' ?>
                </strong>
                <?= AttachmentsUtility::getFormattedAspectRatio($realModel->aspect_ratio) ?>
            <?php } ?>

            <br/>
            <strong class="h4">
                <?= FileModule::t('amosattachments', "Estensione")
                . ': ' ?>
            </strong>
            <?= $model->type ?>

            <br/>
            <strong class="h4">
                <?= FileModule::t('amosattachments', "Peso")
                . ': ' ?>
            </strong>
            <?= $model->formattedSize ?>
            <br/>
        </p>

        <?php if ($tagsImage) : ?>
            <p>
            <h4><?= FileModule::t('amosattachments', "Tag di interesse informativo") ?></h4>
            <div class="content-tag">
                <?= AttachmentsUtility::formatTags($tagsImage) ?>
            </div>
            </p>
        <?php endif; ?>

        <?php if($tagsCustomImage){?>
        <p>
        <h4><?= FileModule::t('amosattachments', "Tag liberi") ?></h4>
        <div class="content-tag">
            <?= AttachmentsUtility::formatTags($tagsCustomImage) ?>
        </div>
        </p>
        <?php } ?>

        <div class="m-t-30">
            <?php
            if (\Yii::$app->user->can('MANAGE_ATTACH_GALLERY')) {
                echo Html::a(FileModule::t('amosattachments', 'Scarica'),
                    [
                        $model->getWebUrl()
                    ],
                    [
                        'class' => 'btn btn btn-primary-outline',
                        'title' => FileModule::t('amosattachments', 'Scarica'),
                        'target' => 'blank'
                    ]);
            }
            echo Html::a(
                FileModule::t('amosattachments', 'Copia url'),
                \Yii::$app->params['platform']['frontendUrl'].$model->getWebUrl(),
                [
                    'title' => FileModule::t('amosattachment', 'Copia url'),
                    'class' => 'btn btn-primary-outline copy-and-paste',
                ]
            );
            ?>
        </div>
    </div>
</div>