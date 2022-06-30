<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-attachments/src/views
 */

/**
 * @var yii\web\View $this
 * @var open20\amos\attachments\models\AttachGalleryRequest $model
 */

use open20\amos\attachments\FileModule;

$this->title = FileModule::t('amosattachments', 'Reply to request image');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/attachments']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Attach Gallery Request'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;



$js = <<<JS
    $(document).on('click','#crop-buttons-id button', function(){
        var crop_type = $(this).attr('data-option');
        console.log(crop_type);
        $('#aspect_ratio_reply_id').val(crop_type);
    })
JS;

$this->registerJs($js);
?>
<div class="attach-gallery-request-update">

    <?php $form = \open20\amos\core\forms\ActiveForm::begin([
        'options' => [
            'id' => 'attach-gallery-request',
        ]
    ]);
    ?>
    <div class="m-t-15">
        <p>
            <strong><?= FileModule::t('amosattachments', "ID Request") . ': ' ?></strong><?= $model->id ?><br>
            <strong><?= FileModule::t('amosattachments', "Request by") . ': ' ?></strong><?= $model->getCreatedByProfile() ?>
            <br>
            <strong><?= FileModule::t('amosattachments', "Request at") . ': ' ?></strong><?= \Yii::$app->formatter->asDate($model->created_at) ?>
            <br>
        </p>

    </div>
    <hr>

    <div class="row">
        <div class="col-xs-12">
            <p>
                <strong><?= FileModule::t('amosattachments', "Tag di interesse informativo richiesti") ?></strong><br>
                <?= $model->getTagImagesString() ?>
            </p>
        </div>

        <div class="col-xs-6 m-t-10">
            <strong><?= FileModule::t('amosattachments', "Tag liberi:") ?></strong><br>
            <?= $model->getCustomTagsString() ?>
            <?= $form->field($model, 'customTagsReply')->widget(\xj\tagit\Tagit::className(), [
                'options' => [
                    'id' => 'custom-tags-id',
                    'placeholder' => FileModule::t('amosattachments', 'Inserisci una parolachiave')
                ],
                'clientOptions' => [
                    'tagSource' => '/attachments/attach-gallery-image/get-autocomplete-tag',
                    'autocomplete' => [
                        'delay' => 200,
                        'minLength' => 2,
                    ],
                ]
            ])->label(false) ?>
        </div>
    </div>
    <div>
        <p>
            <strong><?= FileModule::t('amosattachments', "Aspect ratio:") ?></strong><br>
            <?= \open20\amos\attachments\utility\AttachmentsUtility::getFormattedAspectRatio($model->aspect_ratio) ?>
        </p>
    </div>
    <div>
        <p>
            <strong><?= FileModule::t('amosattachments', "Text request:") ?></strong><br>
            <?= $model->text_request ?>
        </p>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <?=
            $form->field($model, 'attachImage')->widget(\open20\amos\attachments\components\CropInput::classname(),
                [
                    'enableUploadFromGallery' => false,
                    'aspectRatioChoices' => [
                        [
                            'title' => 'Elimina Crop',
                            'value' => 'x',
                            'label' => \open20\amos\core\icons\AmosIcons::show('close'),
                        ],
                        [
                            'title' => 'Fattore di crop libero',
                            'value' => 'NaN',
                            'label' => 'Libero',
                        ],
                        [
                            'title' => '16:9',
                            'value' => '1.7',
                            'label' => '16:9',
                        ],
                        [
                            'title' => '1:1',
                            'value' => '1',
                            'label' => '1:1',
                        ],
                    ],
                    'jcropOptions' => [
                        'aspectRatio' => '1.7',
                        'placeholder' => 'Scegli crop'],
                ])->label(FileModule::t('amosattachments', '#image_field'))
            ?>
        </div>
    </div>

    <hr>
    <div>
        <?= $form->field($model, 'text_reply')->textarea(['rows' => 5])
            ->label(FileModule::t('amosattachments', 'Text reply'));
        ?>
    </div>
    <div style="display:none">
        <?php
        if(empty($model->aspect_ratio)) {
            $model->aspect_ratio = \open20\amos\attachments\models\AttachGalleryImage::DEFAULT_ASPECT_RATIO;
        }?>
        <?= $form->field($model,'aspect_ratio_reply')->hiddenInput(['id' => 'aspect_ratio_reply_id']) ?>
    </div>

    <div>
        <?= \open20\amos\core\forms\CloseSaveButtonWidget::widget([
            'model' => $model,
            'buttonSaveLabel' => FileModule::t('amosattachments', 'Carica'),
            'urlClose' => \Yii::$app->request->referrer
        ]); ?>
    </div>


    <?php \open20\amos\core\forms\ActiveForm::end(); ?>
</div>
