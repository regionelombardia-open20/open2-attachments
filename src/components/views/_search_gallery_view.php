<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\components\views
 * @category   CategoryName
 */

/**
 * @var $attribute string
 * @var $modelSearch \open20\amos\attachments\models\search\AttachGalleryImageSearch
 */

use open20\amos\attachments\FileModule;
use open20\amos\attachments\models\AttachGalleryImage;
use open20\amos\core\forms\ActiveForm;

use kartik\select2\Select2;

use xj\tagit\Tagit;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

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

$form = ActiveForm::begin([
    'method' => 'get',
    'options' => [
        'id' => 'form-gallery-' . $attribute,
        'class' => 'default-form'
    ]
]);

?>

<div class="content-search-gallery- m-b-25">
    <div class="row variable-gutters">
        <div class="col-sm-12">
            <div>
                <?= FileModule::t('amosattachments', 'Cerca immagine') ?>
            </div>
            <div>
                <?= $form->field($modelSearch, 'name')->textInput(['placeholder' => 'inserisci un testo'])->label(false) ?>
            </div>
        </div>

        <?php if ($tagsImage) : ?>
        <div class="col-sm-6">
            <div>
                <?= FileModule::t('amosattachments', 'Tag di interesse informativo') ?>
            </div>

            <?= $form->field($modelSearch, 'tagsImageSearch')->widget(Select2::class, [
                    'data' => ArrayHelper::map($tagsImage, 'id', 'nome'),
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
        <?php endif; ?>
    </div>

    <div class="row variable-gutters">
        <div class="col-sm-6">
            <div>
                <?= FileModule::t('amosattachments', 'Tag liberi') ?>
            </div>
            <div>
            <?= $form->field($modelSearch, 'customTagsSearch')->widget(Tagit::class, [
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
            <?= $form->field($modelSearch, 'aspectRatioSearch')->widget(Select2::class, [
                'data' => $aspectRatio,
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
            <?= Html::a(FileModule::t('amosattachments', 'Cancel'), '', [
                'class' => 'btn btn-secondary btn-sm',
                'id' => 'btn-cancel-gallery-' . $attribute,
                'title' => FileModule::t('amosattachments', 'Cancel')
            ]) ?>
            <?= Html::a(FileModule::t('amosattachments', 'Search'), '#', [
                'class' => 'btn btn-primary btn-sm',
                'id' => 'btn-search-gallery-' . $attribute,
                'title' => FileModule::t('amosattachments', 'Search')
            ]) ?>
        </div>
    </div>

</div>
<?php ActiveForm::end() ?>