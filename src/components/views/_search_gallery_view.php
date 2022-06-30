<?php

/**
 * @var $attribute string
 * @var $modelSearch \open20\amos\attachments\models\search\AttachGalleryImageSearch
 */

use open20\amos\attachments\FileModule;
use open20\amos\core\forms\ActiveForm;



$form = ActiveForm::begin([
    'method' => 'get',
    'options' => [
        'id' => 'form-gallery-' . $attribute,
        'class' => 'default-form'
    ]
]);

?>

<div class="content-search-gallery mb-5">
    <div class="row variable-gutters">
        <div class="col-sm-6">
            <div>
                <?= FileModule::t('amosattachments', 'Title') ?>
            </div>
            <div>
                <?= $form->field($modelSearch, 'name')->textInput(['placeholder' => 'ricerca per name'])->label(false) ?>
            </div>
        </div>


        <div class="col-sm-6">
            <div>
                <?= FileModule::t('amosattachments', 'Tag di interesse informativo') ?>
            </div>

            <?php $tagsImage = \open20\amos\attachments\models\AttachGalleryImage::getTagIntereseInformativo(); ?>
            <?= $form->field($modelSearch, 'tagsImageSearch')->widget(\kartik\select2\Select2::className(), [
                'data' => \yii\helpers\ArrayHelper::map($tagsImage, 'id', 'nome'),
                'options' => [
                    'id' => 'tags-image-id-'.$attribute,
                    'placeholder' => \Yii::t('app', "Seleziona i tag ..."),
                    'multiple' => true,
                    'title' => 'Tag di interesse informativo',
                ],
                'pluginOptions' => ['allowClear' => true]
            ])
                ->label(false); ?>
        </div>
    </div>

    <div class="row variable-gutters">
        <div class="col-sm-6">
            <div>
                <?= FileModule::t('amosattachments', 'Tag liberi') ?>
            </div>
            <div>
                <?= $form->field($modelSearch, 'customTagsSearch')->widget(\xj\tagit\Tagit::className(), [
                    'options' => [
                        'id' => 'custom-tags-search-id-'.$attribute,
                        'placeholder' => FileModule::t('amosattachments', 'Inserisci una parolachiave')
                    ],
                    'clientOptions' => [
                        'tagSource' => '/attachments/attach-gallery-image/get-autocomplete-tag',
                        'autocomplete' => [
                            'delay' => 30,
                            'minLength' => 2,
                        ],
                    ]
                ])->label(false) ?>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="mb-4">
                <?= FileModule::t('amosattachments', 'Aspect ratio') ?>
            </div>
            <div>
                <?=
                $form->field($modelSearch, 'aspectRatioSearch')->widget(\kartik\select2\Select2::className(), [
                    'data' => [
                        '1.7' => '16:9',
                        '1' => '1:1',
                        'other' => FileModule::t('amosattachments', 'Other'),
                    ],
                    'options' => [
                        'placeholder' => FileModule::t('amosattachments', 'Seleziona...'),
                    ],
                    'pluginOptions' => ['allowClear' => true]

                ])->label(false);
                ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= \yii\helpers\Html::a(FileModule::t('amosattachments', 'Cancel'), '', [
                'class' => 'btn btn-secondary btn-sm',
                'id' => 'btn-cancel-gallery-' . $attribute,
                'title' => FileModule::t('amosattachments', 'Cancel')
            ]) ?>
            <?= \yii\helpers\Html::a(FileModule::t('amosattachments', 'Search'), '#', [
                'class' => 'btn btn-primary btn-sm',
                'id' => 'btn-search-gallery-' . $attribute,
                'title' => FileModule::t('amosattachments', 'Search')
            ]) ?>
        </div>
    </div>

</div>
<?php ActiveForm::end() ?>