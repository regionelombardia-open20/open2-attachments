<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-attachments/src/views
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use kartik\datecontrol\DateControl;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use yii\helpers\Url;
use open20\amos\core\forms\editors\Select;
use yii\helpers\ArrayHelper;
use open20\amos\core\icons\AmosIcons;
use yii\bootstrap\Modal;
use open20\amos\core\forms\TextEditorWidget;
use yii\helpers\Inflector;
use \open20\amos\attachments\models\AttachGalleryRequest;
use open20\amos\attachments\FileModule;

/**
 * @var yii\web\View $this
 * @var open20\amos\attachments\models\AttachGalleryRequest $model
 * @var yii\widgets\ActiveForm $form
 */

$module = \Yii::$app->getModule('attachments');
$disableFreeCropGallery = $module->disableFreeCropGallery;

?>
<div class="attach-gallery-request-form">

    <?php $form = ActiveForm::begin([
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
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>

    <div class="row">
       
            <div class="col-sm-6">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?><!-- aspect_ratio string -->
            </div>

            <div class="col-sm-6">
                <?php $tagsImage = \open20\amos\attachments\models\AttachGalleryImage::getTagIntereseInformativo(); ?>
                <?= $form->field($model, 'tagsImage')->widget(\kartik\select2\Select2::className(), [
                    'data' => ArrayHelper::map($tagsImage, 'id', 'nome'),
                    'options' => [
                        'id' => 'tags-image-id',
                        'placeholder' => \Yii::t('app', "Seleziona i tag ..."),
                        'multiple' => true,
                        'title' => 'Tag di interesse informativo',
                    ],
                    'pluginOptions' => ['allowClear' => true]
                ])->label(FileModule::t('amosattachments', 'Tag di interesse informativo')) ?>
            </div>

            <div id="custom-tags-cont" class="col-sm-6">
                <?= $form->field($model, 'customTags')->widget(\xj\tagit\Tagit::className(), [
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
            <div class="col-sm-6">
                <?php
                $aspectRatio = [
                    '1.7' => '16:9',
                    '1' => '1:1',
                ];
                if(!$disableFreeCropGallery){
                    $aspectRatio['other']= FileModule::t('amosattachments', 'Other');
                }
                ?>
                <?= $form->field($model, 'aspect_ratio')->inline(true)->radioList($aspectRatio ) ?><!-- text_request text -->
            </div>

            <div class="col-xs-12">
                <?= $form->field($model, 'text_request')->textarea(['rows' => 5])->label(FileModule::t('amosattachments','Text request'));
                ?>
            </div>


       
<!--        <div class="col-md-4 col-xs-12">-->
<!--            <div class="col-xs-12 nop">-->
<!--                --><?php
//                $form->field($model, 'attachImage')->widget(\open20\amos\attachments\components\CropInput::classname(),
//                    [
//                        'enableUploadFromGallery' => false,
//                        'jcropOptions' => ['aspectRatio' => '1.7']
//                    ])->label(FileModule::t('amosattachments', '#image_field'))
//                ?>
<!--            </div>-->
<!---->
<!--        </div>-->
    </div>
    <div class="row">
        <?php
        echo
        \open20\amos\workflow\widgets\WorkflowTransitionButtonsWidget::widget([
            'form' => $form,
            'model' => $model,
            'workflowId' => AttachGalleryRequest::GALLERY_IMAGE_REQUEST_WORKFLOW,
            'viewWidgetOnNewRecord' => true,
            //'closeSaveButtonWidget' => CloseSaveButtonWidget::widget($config),
            'closeButton' => Html::a(FileModule::t('amosattachments', 'Annulla'),
                ['/attachments/attach-gallery/single-gallery'], ['class' => 'btn btn-secondary']),
            'initialStatusName' => "OPENED",
            'initialStatus' => AttachGalleryRequest::GALLERY_IMAGE_REQUEST_WORKFLOW,
            'statusToRender' => [
                AttachGalleryRequest::IMAGE_REQUEST_WORKFLOW_STATUS_OPENED => 'Modifica in corso'
            ],
            //gli utenti validatore/facilitatore o ADMIN possono sempre salvare la news => parametro a false
            //altrimenti se stato VALIDATO => pulsante salva nascosto
            //'hideSaveDraftStatus' => $statusToRenderToHide['hideDraftStatus'],
            'additionalButtons' => [
                //                'default' => [
                //                    [
                //                        'button' => $buttonSalvaAndUpload,
                //                        'description' => '',
                //                    ],
                //                ]
            ],
            'draftButtons' => [
                'default' => [
                    'button' => Html::submitButton(FileModule::t('amosattachments', 'Richiedi'),
                        ['class' => 'btn btn-workflow']),
//                    'description' => FileModule::t('amosattachments',
//                        'Invia richiesta'),
                ]
            ],
        ]);
        ?>


    </div>

    <?php ActiveForm::end(); ?>

</div>
