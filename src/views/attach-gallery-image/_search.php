<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-attachments/src/views
 */

use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use open20\amos\attachments\FileModule;

/**
 * @var yii\web\View $this
 * @var open20\amos\attachments\models\search\AttachGalleryImageSearch $model
 * @var yii\widgets\ActiveForm $form
 */
$module = \Yii::$app->getModule('attachments');
$disableFreeCropGallery = $module->disableFreeCropGallery;

?>
<div class="attach-gallery-image-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
        'action' => (isset($originAction) ? [$originAction] : ['index']),
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>

    <!-- id --> <?php // echo $form->field($model, 'id') 
    ?>


    <div class="row">
        <!-- category_id -->
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['placeholder' => 'ricerca per nome']) ?>
        </div>

        <div class="col-md-6">
            <?php $tagsImage = \open20\amos\attachments\models\AttachGalleryImage::getTagIntereseInformativo(); ?>
            <?= $form->field($model, 'tagsImageSearch')->widget(\kartik\select2\Select2::className(), [
                'data' => \yii\helpers\ArrayHelper::map($tagsImage, 'id', 'nome'),
                'options' => [
                    'id' => 'tags-image-id',
                    'placeholder' => \Yii::t('app', "Seleziona i tag ..."),
                    'multiple' => true,
                    'title' => 'Tag di interesse informativo',
                ],
                'pluginOptions' => ['allowClear' => true]
            ])
                ->label(FileModule::t('amosattachments', "Tag di interesse informativo")); ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'customTagsSearch')->widget(\xj\tagit\Tagit::className(), [
                'options' => [
                    'id' => 'custom-tags-id',
                    'placeholder' => FileModule::t('amosattachments', 'Inserisci una parolachiave')
                ],
                'clientOptions' => [
                    'tagSource' => '/attachments/attach-gallery-image/get-autocomplete-tag',
                    'autocomplete' => [
                        'delay' => 30,
                        'minLength' => 2,
                    ],
                ]
            ])->label(FileModule::t('amosevents', 'Tag liberi')) ?>
        </div>

        <div class="col-md-6">
            <?php
            $aspectRatio = [
                '1.7' => '16:9',
                '1' => '1:1',
            ];
            if(!$disableFreeCropGallery){
                $aspectRatio['other']= FileModule::t('amosattachments', 'Other');
            }
            ?>
            <?=
            $form->field($model, 'aspectRatioSearch')->widget(\kartik\select2\Select2::className(), [
                'data' => $aspectRatio,
                'options' => [
                    'placeholder' => FileModule::t('amosattachments', 'Seleziona...'),
                ],
                'pluginOptions' => ['allowClear' => true]

            ])->label('Aspect ratio');
            ?>
        </div>
    </div>
    <div class="row">


        <div class="col-md-6">
            <?= $form->field($model, 'created_at')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE
            ])->label(FileModule::t('amosattachments', 'Creata a partire dal')) ?>
        </div>

        <div class="col-md-6">
            <?php
            $creator = '';
            $manageUserIds = \Yii::$app->authManager->getUserIdsByRole('MANAGE_ATTACH_GALLERY');
            $OperatorUserIds = \Yii::$app->authManager->getUserIdsByRole('ATTACH_IMAGE_REQUEST_OPERATOR');
            $userIds = \yii\helpers\ArrayHelper::merge($manageUserIds, $OperatorUserIds);

            $creatorsQuery = \open20\amos\admin\models\UserProfile::find()
                ->andWhere(['user_id' => $userIds])
                ->andWhere(['NOT LIKE', 'nome', '########'])
                ->andWhere(['attivo' => 1])
                ->orderBy('nome, cognome ASC');


            echo $form->field($model, 'created_by')->widget(
                \kartik\select2\Select2::className(),
                [
                    'data' => \yii\helpers\ArrayHelper::map($creatorsQuery->all(), 'id', 'nomeCognome'),
                    'options' => ['placeholder' => FileModule::t('amosattachments', 'Seleziona ...')],
                ]
            )->label(FileModule::t('amosattachments', 'Creata da'));
            ?>
        </div>
    </div>

    <!-- deleted_by --> <?php // echo $form->field($model, 'deleted_by') 
    ?>
    <div class="row">


        <div class="col-xs-12 m-b-10">
            <div class="pull-right">
                <?= Html::a(Yii::t('amoscore', 'Reset'), '/attachments/attach-gallery/single-gallery', ['class' => 'btn btn-secondary']) ?>
                <?= Html::submitButton(Yii::t('amoscore', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>