<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-attachments/src/views
 */

use open20\amos\attachments\FileModule;
use open20\amos\attachments\components\CropInput;
use open20\amos\attachments\models\AttachGallery;
use open20\amos\attachments\models\AttachGalleryImage;
use open20\amos\attachments\utility\AttachmentsUtility;
use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\icons\AmosIcons;

use kartik\datecontrol\DateControl;
use kartik\select2\Select2;

use xj\tagit\Tagit;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\redactor\widgets\Redactor;
use yii\helpers\Inflector;

/**
 * @var yii\web\View $this
 * @var open20\amos\attachments\models\AttachGalleryImage $model
 * @var yii\widgets\ActiveForm $form
 */
$js = <<<JS
    $(document).on('click','#crop-buttons-id button', function(){
        var crop_type = $(this).attr('data-option');
        $('#aspect_ratio_id').val(crop_type);
    })
JS;

$this->registerJs($js);

$module = \Yii::$app->getModule('attachments');
$enableSingleGallery = $module->enableSingleGallery;
$disableFreeCropGallery = $module->disableFreeCropGallery;
$luyaGalleryFolderId = $module->luyaGalleryFolderId;

$tagsImage = AttachGalleryImage::getTagIntereseInformativo();

if (empty($model->aspect_ratio)) {
    $model->aspect_ratio = AttachGalleryImage::DEFAULT_ASPECT_RATIO;
}

$append = \Yii::$app->getUser()->can('ATTACHGALLERY_CREATE')
    ? ' canInsert'
    : null;

$idGallery = \Yii::$app->request->get('id');

$display = !empty($idGallery)
    ? 'display:none'
    : '';

$aspectRatioChoices = AttachmentsUtility::getConfigCropGallery();

$form = ActiveForm::begin([
    'options' => [
        'id' => 'attach-gallery-image_' . ((isset($fid)) ? $fid : 0),
        'data-fid' => (isset($fid)) ? $fid : 0,
        'data-field' => ((isset($dataField)) ? $dataField : ''),
        'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
        'class' => ((isset($class)) ? $class : ''),
        'enctype' => 'multipart/form-data'
    ]
]);
?>

<div class="attach-gallery-image">
    <p><?= FileModule::t('amosattachments', "Carica un immagine dal tuo dispositivo oppure cercane una su <a href='/attachments/shutterstock/index'>Shutterstock</a>")?></p>
    <div class="row m-t-20">
        <div class="col-md-6">
            <?php
            $hideImage = '';
            if (!empty($luyaGalleryFolderId) && !empty($model->attachImage)) {
                $hideImage = 'display:none;'; ?>
                <?= Html::img($model->attachImage->getWebUrl(), ['class' => 'img-responsive']) ?>
            <?php } ?>
            <div style="<?= $hideImage ?>">
                <?= $form->field($model, 'attachImage')->widget(CropInput::class, [
                    'hidePreviewDeleteButton' => true,
                    'enableUploadFromGallery' => false,
                    'enableUploadFromShutterstock' => false,
                    'aspectRatioChoices' => $aspectRatioChoices,
                    'jcropOptions' => ['aspectRatio' => '1.7']
                ])
                    ->label(
                        FileModule::t('amosattachments', '#image_field')
                    )
                ?>
            </div>
        </div>
        <div class="col-md-6">
            <div>
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>

            <div>
                <div style="<?= $display ?>">
                    <?= $form->field($model, 'gallery_id')->widget(Select::class, [
                        'data' => ArrayHelper::map(
                            AttachGallery::find()
                                ->asArray()
                                ->all(),
                            'id',
                            'name'
                        ),
                        'language' => substr(Yii::$app->language, 0, 2),
                        'options' => [
                            'id' => 'AttachGallery' . $fid,
                            'multiple' => false,
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])
                    ?>
                </div>
            </div>



            <div style="display:none">
                <?= $form->field($model, 'aspect_ratio')->hiddenInput(['id' => 'aspect_ratio_id']) ?>
            </div>

        </div>


        <div class="col-md-12">
            <?php if ($tagsImage) : ?>
                <div>
                    <?= $form->field($model, 'tagsImage')->widget(Select2::class, [
                        'data' => ArrayHelper::map($tagsImage, 'id', 'nome'),
                        'options' => [
                            'id' => 'tags-image-id',
                            'placeholder' => FileModule::t('amosattachments', '#placeholder_for_tags'),
                            'multiple' => true,
                            'title' => 'Tag di interesse informativo',
                        ],
                        'pluginOptions' => ['allowClear' => true]
                    ])->label(FileModule::t('amosattachments', 'Tag di interesse informativo'))
                    ?>
                </div>
            <?php endif; ?>

            <div id="custom-tags-cont">
                <?= $form->field($model, 'customTags')->widget(Tagit::class, [
                    'options' => [
                        'id' => 'custom-tags-id',
                        'placeholder' => FileModule::t('amosattachments', 'Inserisci una parolachiave')
                    ],
                    'clientOptions' => [
                        'tagSource' => '/attachments/attach-gallery-image/get-autocomplete-tag',
                        'autocomplete' => [
                            'delay' => 20,
                            'minLength' => 2,
                        ],
                    ]
                ])->label(FileModule::t('amosevents', 'Tag'))->hint("Inserisci delle etichette di codifica per rendere più semplice la ricerca all’interno della piattaforma.") ?>
            </div>
        </div>
        <div class="clearfix"></div>

    </div>

    <div>
        <?= RequiredFieldsTipWidget::widget(); ?>

        <?= CloseSaveButtonWidget::widget([
            'model' => $model,
            'urlClose' => \Yii::$app->request->referrer,
            'closeButtonLabel' => FileModule::t('amosattachments', 'Indietro'),
            'buttonNewSaveLabel' => FileModule::t('amosattachments', 'Carica')
        ]); ?>
    </div>

</div>

<?php ActiveForm::end(); ?>