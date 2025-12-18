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
use open20\amos\attachments\models\AttachFileCategorie;
use open20\amos\attachments\models\AttachGalleryImage;
use open20\amos\core\forms\ActiveForm;

use open20\amos\core\forms\editors\Select;

use xj\tagit\Tagit;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var FileModule $module */
$module = \Yii::$app->getModule('attachments');

$form = ActiveForm::begin([
    'method' => 'get',
    'options' => [
        'id' => 'form-databank-file-' . $attribute,
        'class' => 'default-form'
    ]
]);

?>

<div class="content-search-databank-file mb-5">
    <div class="row variable-gutters">
        <div class="col-sm-4">
            <div>
                <?= FileModule::t('amosattachments', 'Nome file') ?>
            </div>
            <div>
                <?= $form->field($modelSearch, 'name')->textInput(['placeholder' => \Yii::t('amosattachments','ricerca per nome file')])->label(false) ?>
            </div>
        </div>

        <div class="col-sm-4">
            <div>
                <?= FileModule::t('amosattachments', 'Estensione') ?>
            </div>
            <div>
                <?= $form->field($modelSearch, 'extension')->textInput(['placeholder' => \Yii::t('amosattachments','ricerca per estensione')])->label(false) ?>
            </div>
        </div>

        <?php if($module->categorizationEnabled && property_exists($modelSearch, 'attachmentCatSearch')) : ?>
            <div class="col-sm-4">
                <div>
                    <?= FileModule::t('amosattachments', 'Categoria') ?>
                </div>
                <div>
                    <?= $form->field($modelSearch, 'attachmentCatSearch')->widget(Select::class, [
                        'data' => ArrayHelper::map(
                            AttachFileCategorie::find()->select(['id', 'titolo'])->asArray()->all(),
                            'id', 'titolo'),
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                        'options' => [
                            'multiple' => false,
                            'placeholder' => FileModule::t('amosattachments', 'Seleziona Categoria')
                        ]
                    ])->label(false);//textInput(['placeholder' => \Yii::t('amosattachments','')])->label(false) ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="col-sm-12">
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
                        'tagSource' => '/attachments/attach-databank-file/get-autocomplete-tag',
                        'autocomplete' => [
                            'delay' => 30,
                            'minLength' => 2,
                        ],
                    ]
                ])->label(false) ?>
            </div>
        </div>
        <div class="col-md-12 text-right m-b-10">
            <?= Html::a(FileModule::t('amosattachments', 'Cancel'), '', [
                'class' => 'btn btn-secondary',
                'id' => 'btn-cancel-databank-file-' . $attribute,
                'title' => FileModule::t('amosattachments', 'Cancel')
            ]) ?>
            <?= Html::a(FileModule::t('amosattachments', 'Search'), '#', [
                'class' => 'btn btn-primary',
                'id' => 'btn-search-databank-file-' . $attribute,
                'title' => FileModule::t('amosattachments', 'Search')
            ]) ?>
        </div>                
    </div>


</div>
<?php ActiveForm::end() ?>