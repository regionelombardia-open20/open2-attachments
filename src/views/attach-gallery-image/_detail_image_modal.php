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

use yii\helpers\Html;

/**
 * @var $attribute string
 * @var $model \open20\amos\attachments\models\AttachGalleryImage
 */

$fileImage = $model->attachImage;

$image = Html::img($model->attachImage->getWebUrl(), [
    'class' => 'img-responsive'
]);

list($width, $height, $type, $attr) = getimagesize($fileImage->path);

$tagsImage = $model->getTagsImageModel();
$tagsCustomImage = $model->getCustomTagsModel();
?>

<div class="row detail-image-masonry m-t-20 m-b-20">
    <div class="col-sm-8 image-detail">
    <?= $image; ?>
    </div>

    <div class="col-sm-4">
    <?php
        if (empty($isView)) {
            echo Html::tag('h3', $model->name);
        }
    ?>
        <p>
            <strong class="h4">
            <?= FileModule::t('amosattachments', "Published by")
                . ': ' ?>
            </strong>
            <?= $model->getCreatedByProfile() ?>
            <br />
            <strong class="h4">
            <?= FileModule::t('amosattachments', "at")
                . ': ' ?>
            </strong>
            <?= \Yii::$app->formatter->asDate($model->created_at) ?>
        </p>
        
        <p>
            <strong class="h4">
            <?= FileModule::t('amosattachments', "Dimensioni (pixel)")
                . ': ' ?>
            </strong>
            <?= $width . 'x' . $height ?>
            
            <br />
            <strong class="h4">
            <?= FileModule::t('amosattachments', "Aspect ratio")
                . ': ' ?>
            </strong>
            <?= AttachmentsUtility::getFormattedAspectRatio($model->aspect_ratio) ?>
            
            <br />
            <strong class="h4">
            <?= FileModule::t('amosattachments', "Estensione")
                . ': ' ?>
            </strong>
            <?= $fileImage->type ?>
            
            <br />
            <strong class="h4">
            <?= FileModule::t('amosattachments', "Peso")
            . ': ' ?>
            </strong>
            <?= $fileImage->formattedSize ?>
            <br />
        </p>

        <?php if ($tagsImage) : ?>
        <p>
            <h4><?= FileModule::t('amosattachments', "Tag di interesse informativo") ?></h4>
            <div class="content-tag">
            <?= AttachmentsUtility::formatTags($tagsImage) ?>
            </div>
        </p>
        <?php endif; ?>

        <p>
            <h4><?= FileModule::t('amosattachments', "Tag liberi") ?></h4>
            <div class="content-tag">
            <?= AttachmentsUtility::formatTags($tagsCustomImage) ?>
            </div>
        </p>

        <div class="m-t-30">
        <?php
        if (\Yii::$app->user->can('MANAGE_ATTACH_GALLERY')) {
            echo Html::a(FileModule::t('amosattachments', 'Download'),
                [
                    $model->attachImage->getWebUrl()
                ],
                [
                    'class' => 'btn btn-primary',
                    'title' => FileModule::t('amosattachments', 'Download'),
                ]);
        }
        
        if (\Yii::$app->user->can('ATTACHGALLERYIMAGE_UPDATE', ['model' => $model])) {
            echo Html::a(FileModule::t('amosattachments', 'Modifica'),
                [
                    '/attachments/attach-gallery-image/update',
                    'id' => $model->id
                ],
                [
                    'class' => 'btn btn-secondary',
                    'title' => FileModule::t('amosattachments', 'Modifica'),
                ]);
        }
        
        if (\Yii::$app->user->can('ATTACHGALLERYIMAGE_DELETE', ['model' => $model])) {
            echo Html::a(FileModule::t('amosattachments', 'Elimina'),
                [
                    '/attachments/attach-gallery-image/delete',
                    'id' => $model->id
                ],
                [
                    'class' => 'btn btn-danger',
                    'data-confirm' => FileModule::t(
                        'amosattachments',
                        'Sei sicuro di cancellare questa immagine?'
                    ),
                    'title' => FileModule::t('amosattachments', 'Elimina'),
                ]
            );
        }
        ?>
        </div>
    </div>
</div>