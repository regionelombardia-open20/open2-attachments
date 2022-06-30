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
use yii\redactor\widgets\Redactor;
use yii\helpers\Inflector;
use open20\amos\attachments\FileModule;

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
?>
<div class="attach-gallery-image-form">

    <?php $form = ActiveForm::begin([
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
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>
    <div class="row m-t-20">
        <div class="col-md-8">
            <!-- name string -->
            <div>
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?><!-- description text -->
            </div>
            <div>
                <?php
                if (\Yii::$app->getUser()->can('ATTACHGALLERY_CREATE')) {
                    $append = ' canInsert';
                } else {
                    $append = NULL;
                }
                ?>

                <?php
                $display = '';
                $idGallery = \Yii::$app->request->get('id');

                if (!empty($idGallery)) {
                    $display = 'display:none';
                } ?>
                <div style="<?= $display ?>">
                    <?= $form->field($model, 'gallery_id')->widget(Select::classname(), [
                        'data' => ArrayHelper::map(\open20\amos\attachments\models\AttachGallery::find()->asArray()->all(), 'id', 'name'),
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
            <div>
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
            <div id="custom-tags-cont">
                <?= $form->field($model, 'customTags')->widget(\xj\tagit\Tagit::className(), [
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
                ])->label(FileModule::t('amosevents', 'Tag liberi')) ?>
            </div>

            <!--                <div class="col-md-6 col xs-12">-->
            <!--                    --><?php
            //                    if (\Yii::$app->getUser()->can('ATTACHGALLERYCATEGORY_CREATE')) {
            //                        $append = ' canInsert';
            //                    } else {
            //                        $append = NULL;
            //                    }
            //                    ?>
            <!--                --><?php //$form->field($model, 'category_id')->widget(Select::classname(), [
            //                    'data' => ArrayHelper::map(\open20\amos\attachments\models\AttachGalleryCategory::find()->asArray()->all(),'id','name'),
            //                    'language' => substr(Yii::$app->language, 0, 2),
            //                    'options' => [
            //                        'id' => 'AttachGalleryCategory' . $fid,
            //                        'multiple' => false,
            //                        'placeholder' => 'Seleziona ...',
            //                        'class' => 'dynamicCreation' . $append,
            //                        'data-model' => 'attach_gallery_category',
            //                        'data-field' => 'name',
            //                        'data-module' => 'attachments',
            //                        'data-entity' => 'attach-gallery-category',
            //                        'data-toggle' => 'tooltip'
            //                    ],
            //                    'pluginOptions' => [
            //                        'allowClear' => true
            //                    ],
            //                    'pluginEvents' => [
            //                        "select2:open" => "dynamicInsertOpening"
            //                    ]
            //                ]);
            ?>
            <!--                </div>-->
            <!--                --><?php //$form->field($model, 'description')->widget(yii\redactor\widgets\Redactor::className(), [
            //                    'options' => [
            //                        'id' => 'description' . $fid,
            //                    ],
            //                    'clientOptions' => [
            //                        'language' => substr(Yii::$app->language, 0, 2),
            //                        'plugins' => ['clips', 'fontcolor', 'imagemanager'],
            //                        'buttons' => ['format', 'bold', 'italic', 'deleted', 'lists', 'image', 'file', 'link', 'horizontalrule'],
            //                    ],
            //                ]);
            //                ?>

            <div style="display:none">
                <?php
                if (empty($model->aspect_ratio)) {
                    $model->aspect_ratio = \open20\amos\attachments\models\AttachGalleryImage::DEFAULT_ASPECT_RATIO;
                } ?>
                <?= $form->field($model, 'aspect_ratio')->hiddenInput(['id' => 'aspect_ratio_id']) ?>
            </div>

        </div>

        <div class="col-md-4">
            <?php $aspectRatioChoices = \open20\amos\attachments\utility\AttachmentsUtility::getConfigCropGallery(); ?>
            <div>
                <?=
                $form->field($model, 'attachImage')->widget(\open20\amos\attachments\components\CropInput::classname(),
                    [
                        'hidePreviewDeleteButton' => true,
                        'enableUploadFromGallery' => false,
                        'aspectRatioChoices' => $aspectRatioChoices,

                        'jcropOptions' => ['aspectRatio' => '1.7']
                    ])->label(FileModule::t('amosattachments', '#image_field') . "<span class='text-danger'> *</span>")
                ?>
            </div>

        </div>
        <div class="clearfix"></div>

    </div>

    <div>
        <?= RequiredFieldsTipWidget::widget(); ?>

        <?= CloseSaveButtonWidget::widget([
            'model' => $model,
            'urlClose' => \Yii::$app->request->referrer
        ]); ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
