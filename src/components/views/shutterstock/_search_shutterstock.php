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
use open20\amos\core\forms\ActiveForm;
use open20\amos\attachments\models\Shutterstock;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

use yii\helpers\Html;

$module = \Yii::$app->getModule('attachments');

$form = ActiveForm::begin([
    'method' => 'get',
    'options' => [
        'id' => 'form-shutterstock-' . $attribute,
        'class' => 'default-form'
    ]
]);

?>

    <div class="content-search-shutterstock mb-5">
        <div class="row variable-gutters">
            <div class="col-sm-12">
                <div>
                    <?= FileModule::t('amosattachments', 'Cerca immagine') ?>
                </div>
                <div>
                    <?= $form->field($modelSearch, 'query')
                        ->textInput([
                            'placeholder' => 'inserisci un testo',
                            'id' => 'id-query-' . $attribute

                        ])
                        ->label(FileModule::t('amosattachments', 'Nome'))
                        ->label(false)
                    ?>
                </div>
                <div style="display:none" id="suggestions-<?=$attribute?>"><ul></ul></div>
            </div>
        </div>

        <div class="row variable-gutters">
            <div class="col-sm-4">
                <div>
                    <?= FileModule::t('amosattachments', 'Tipo di immagine') ?>
                </div>
                <div>
                    <?= $form->field($modelSearch, 'image_type')->widget(\kartik\select2\Select2::className(), [
                        'data' => Shutterstock::imageTypes(),
                        'options' => ['placeholder' => 'Seleziona...'],
                        'pluginOptions' => ['allowClear' => true]

                    ])->label(false)
                    ?>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="mb-4">
                    <?= FileModule::t('amosattachments', 'Orientamento') ?>
                </div>

                <div>
                    <?= $form->field($modelSearch, 'orientation')->widget(\kartik\select2\Select2::className(), [
                        'data' => Shutterstock::orientationTypes(),
                        'options' => ['placeholder' => 'Seleziona...'],
                        'pluginOptions' => ['allowClear' => true]

                    ])->label(false)
                    ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div>
                    <?= FileModule::t('amosattachments', 'Ordinamento') ?>
                </div>

                <?= $form->field($modelSearch, 'sort')->widget(\kartik\select2\Select2::className(), [
                    'data' => Shutterstock::orderTypes(),
                    'options' => ['placeholder' => 'Seleziona...'],
                    'pluginOptions' => ['allowClear' => true]

                ])->label(false) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 text-right">
                <?= Html::a(FileModule::t('amosattachments', 'Cancel'), '', [
                    'class' => 'btn btn-secondary btn-sm',
                    'id' => 'btn-cancel-shutterstock-' . $attribute,
                    'title' => FileModule::t('amosattachments', 'Cancel')
                ]) ?>
                <?= Html::a(FileModule::t('amosattachments', 'Search'), '#', [
                    'class' => 'btn btn-primary btn-sm',
                    'id' => 'btn-search-shutterstock-' . $attribute,
                    'title' => FileModule::t('amosattachments', 'Search')
                ]) ?>
            </div>
        </div>

    </div>
<?php ActiveForm::end() ?>