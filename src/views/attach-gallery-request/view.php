<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-attachments/src/views
 */

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use \open20\amos\attachments\FileModule;

/**
 * @var yii\web\View $this
 * @var open20\amos\attachments\models\AttachGalleryImage $model
 */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/attachments']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Attach Gallery Image'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$fileImage = $model->attachImage;
?>
<div class="attach-gallery-image-view">
    <?php $urlImage = '/img/img_default.jpg';
    if ($fileImage) {
        $urlImage = $model->attachImage->getWebUrl();
    }
    ?>

    <div class="row m-t-15">
        <div class="col-md-3">
            <p><strong><?= FileModule::t('amosattachments', "ID Request") . ': ' ?></strong></p>
        </div>
        <div class="col-md-9">
            <p><?= $model->id ?></p>
        </div>
        <div class="col-md-3">
            <p><strong><?= FileModule::t('amosattachments', "Request by") . ': ' ?></strong></p>
        </div>
        <div class="col-md-9">
            <p><?= $model->getCreatedByProfile() ?></p>
        </div>
        <div class="col-md-3">
            <p><strong><?= FileModule::t('amosattachments', "Request at") . ': ' ?></strong></p>
        </div>
        <div class="col-md-9">
            <p><?= \Yii::$app->formatter->asDate($model->created_at) ?></p>
        </div>

    </div>
    <hr>

    <div class="row">
        <div class="col-md-3">
            <p><strong><?= FileModule::t('amosattachments', "Tag di interesse informativo richiesti") . ':' ?></strong></p>

        </div>
        <div class="col-md-9">
            <p>
                <?php $tagsImage = $model->getTagsImageModel();
                foreach ($tagsImage as $tagImage) {
                    echo Html::tag('span', $tagImage->nome, ['class' => 'label label-default']);
                } ?>
            </p>
        </div>
    </div>
    <div class="row m-t-10">

        <div class="col-md-3">
            <p><strong><?= FileModule::t('amosattachments', "Tag liberi") . ':' ?></strong></p>
        </div>
        <div class="col-md-9">
            <p>
                <?php $tagsImage = $model->getCustomTagsModel();
                foreach ($tagsImage as $tagImage) {
                    echo Html::tag('span', $tagImage->nome, ['class' => 'label label-default']);
                } ?>
            </p>
        </div>

    </div>
    <hr>
    <div class="row m-t-15">
        <div class="col-md-3">
            <p>
                <strong><?= FileModule::t('amosattachments', "Aspect ratio") . ':' ?></strong>

            </p>
        </div>
        <div class="col-md-9">
            <p><?= \open20\amos\attachments\utility\AttachmentsUtility::getFormattedAspectRatio($model->aspect_ratio) ?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <p> <strong><?= FileModule::t('amosattachments', "Text request") . ':' ?></strong></p>
        </div>
        <div class="col-md-9">
            <p> <?= $model->text_request ?></p>
        </div>
    </div>


    <?php if ($model->status == \open20\amos\attachments\models\AttachGalleryRequest::IMAGE_REQUEST_WORKFLOW_STATUS_CLOSED) { ?>
        <hr class="m-b-20">
        <div class="row">


            <?php if ($urlImage) { ?>
                <div class="col-md-6">
                    <?= Html::img($urlImage, [
                        'class' => 'img-responsive'
                    ]) ?>
                </div>
            <?php } else { ?>
                <div class="col-md-6">
                    <p>
                        <strong><?= FileModule::t('amosattachments', "Image") ?></strong><br>
                        <?= FileModule::t('amosattachments', "To upload") ?>
                    </p>
                </div>
            <?php } ?>

            <div class="col-md-6">
                <?php
                $imageCloned = $model->attachGalleryImage;
                ?>
                <p class="m-t-15">
                    <?php if ($imageCloned) { ?>
                        <strong><?= FileModule::t('amosattachments', "Closed by") . ': ' ?></strong><?= $imageCloned->getCreatedByProfile() ?>
                        <br>
                        <strong><?= FileModule::t('amosattachments', "Closed at") . ': ' ?></strong><?= \Yii::$app->formatter->asDate($imageCloned->created_at) ?>
                        <br>
                    <?php } ?>
                    <p>
                    <strong><?= FileModule::t('amosattachments', "Text reply") . ': ' ?></strong></p>
                    <?= $model->text_reply ?>
                </p>
            </div>
        <?php } ?>
        </div>

</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(Yii::t('amoscore', 'Chiudi'), \Yii::$app->request->referrer, ['class' => 'btn btn-secondary']); ?></div>