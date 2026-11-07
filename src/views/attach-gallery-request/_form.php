<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-attachments/src/views
 */

use open20\amos\attachments\FileModule;
use open20\amos\attachments\models\AttachGalleryImage;
use open20\amos\attachments\models\AttachGalleryRequest;
use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\icons\AmosIcons;
use open20\amos\workflow\widgets\WorkflowTransitionButtonsWidget;

use kartik\datecontrol\DateControl;
use kartik\select2\Select2;

use xj\tagit\Tagit;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\helpers\Inflector;

/**
 * @var yii\web\View $this
 * @var open20\amos\attachments\models\AttachGalleryRequest $model
 * @var yii\widgets\ActiveForm $form
 */

$module = \Yii::$app->getModule('attachments');
$disableFreeCropGallery = $module->disableFreeCropGallery;

$tagsImage = AttachGalleryImage::getTagIntereseInformativo();
$aspectRatio = [
    '1.7' => '16:9',
    '1' => '1:1',
];

if (!$disableFreeCropGallery) {
    $aspectRatio['other']= FileModule::t('amosattachments', 'Other');
}
?>
<div class="attach-gallery-request-form">
    <?php
    $form = ActiveForm::begin([
        'options' => [
            'id' => 'attach-gallery-request_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : ''),
            'enctype' => 'multipart/form-data'
        ]
    ]);
    ?>
    <div class="row">       
        <div class="col-sm-6">
        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>

        <?php if ($tagsImage) : ?>
        <div class="col-sm-6">
        <?= $form->field($model, 'tagsImage')->widget(Select2::class, [
            'data' => ArrayHelper::map($tagsImage, 'id', 'nome'),
            'options' => [
                'id' => 'tags-image-id',
                'placeholder' => \Yii::t('app', "Seleziona i tag ..."),
                'multiple' => true,
                'title' => 'Tag di interesse informativo',
            ],
            'pluginOptions' => ['allowClear' => true]
            ])
            ->label(FileModule::t('amosattachments', 'Tag di interesse informativo'))
        ?>
        </div>
        <?php endif; ?>

        <div id="custom-tags-cont" class="col-sm-6">
        <?= $form->field($model, 'customTags')->widget(Tagit::class, [
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
        ])
        ->label(FileModule::t('amosevents', 'Tag liberi'))
        ?>
        </div>
            
        <div class="col-sm-6">
        <?= $form->field($model, 'aspect_ratio')
            ->inline(true)
            ->radioList($aspectRatio )
        ?>
        </div>

        <div class="col-xs-12">
        <?= $form->field($model, 'text_request')
            ->textarea(['rows' => 5])
            ->label(FileModule::t('amosattachments','Text request'));
        ?>
        </div>
    </div>
    
    <div class="row">
    <?= WorkflowTransitionButtonsWidget::widget([
        'form' => $form,
        'model' => $model,
        'workflowId' => AttachGalleryRequest::GALLERY_IMAGE_REQUEST_WORKFLOW,
        'viewWidgetOnNewRecord' => true,
        'closeButton' => Html::a(
            FileModule::t('amosattachments', 'Annulla'),
            ['/attachments/attach-gallery/single-gallery'],
            ['class' => 'btn btn-secondary']
        ),
        'initialStatusName' => "OPENED",
        'initialStatus' => AttachGalleryRequest::GALLERY_IMAGE_REQUEST_WORKFLOW,
        'statusToRender' => [
            AttachGalleryRequest::IMAGE_REQUEST_WORKFLOW_STATUS_OPENED => 'Modifica in corso'
        ],
        'additionalButtons' => [],
        'draftButtons' => [
            'default' => [
                'button' => Html::submitButton(
                    FileModule::t('amosattachments', 'Richiedi'),
                    ['class' => 'btn btn-workflow']),
                ]
            ],
        ]);
        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
