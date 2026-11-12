<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-attachments
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
 */

use open20\amos\attachments\models\File;
use open20\amos\attachments\models\AttachFileCategorie;
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
use open20\amos\attachments\FileModule;
use xj\tagit\Tagit;
use open20\amos\attachments\components\AttachmentsInput;

/**
 * @var yii\web\View $this
 * @var open20\amos\attachments\models\AttachDatabankFile $model
 * @var yii\widgets\ActiveForm $form
 */

$module = \Yii::$app->getModule('attachments');
$selectedFile = FileModule::t('amosattachments', 'Verrà assegnato in automatico il nome del file caricato');
$this->registerJsVar('noFileSelectedLabel', $selectedFile);

$js = <<<JS
    var firstLoad = 0;
    $('#attachment-file-id').change(function(e){
        var filename = e.currentTarget.files[0].name;
        var filenameShort = filename.split('.').slice(0, -1).join('.');
            $('#name-id').val(filenameShort);
            $('#name-id-label').text(filename);
    });

$(document).on('DOMSubtreeModified', '#errorDropUpload-attachmentFile', function(){
      if(!$(this).is(':empty')){
            $('#name-id-label').text(noFileSelectedLabel);
        }
});
JS;

$this->registerJs($js);


$fileExist = false;
if ($model->attachmentFile) {
    $fileExist = true;
    $selectedFile = $model->attachmentFile->name . '.' . $model->attachmentFile->type;
}
?>
<div class="attach-databank-file-form col-xs-12 nop">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'attach-databank-file_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : '')
        ]
    ]);
    ?>
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); 
    ?>

    <div class="row m-t-20">
        <?php if (!$fileExist) {
            $extensions = $module->whiteListExtensions;
            $hint = '';
            if ($extensions) {
                $hint = FileModule::t('amosattachments', "Estensioni consentite") . ': ' . implode(', ', $extensions);
                $hint = Html::tag('span','',['class' => 'am am-info-outline m-l-5', 'data-toggle' => 'tooltip', 'title' => $hint]);
            } ?>
            <div class="col-md-6">

                <?= $form->field($model, 'attachmentFile')->widget(AttachmentsInput::class, [
                    'options' => [ // Options of the Kartik's FileInput widget
                        'multiple' => false, // If you want to allow multiple upload, default to false
                        'id' => 'attachment-file-id',
                    ],
                    'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
                        'maxFileCount' => 1, // Client max files
                        'showPreview' => false,
                        'msgPlaceholder' => FileModule::t('amosattachments', 'Nessun allegato caricato')

                    ],
                    'enableUploadFormDatabankFile' => false,

                ])
                    ->label(FileModule::t('amosattachments', 'Allegato') . $hint);
                    // ->hint($hint);
                ?>

            </div>
        <?php } ?>
        <div class="col-md-6">

            <div style="display: none">
                <?= $form->field($model, 'name')->textInput(['id' => 'name-id', 'maxlength' => true]) ?>
            </div>

            <div class="row m-b-10 m-t-15">
                <div class="col-md-12">
                    <label><?= FileModule::t('amosattachments', 'Nome del file') ?></label><br>
                    <span id="name-id-label"><?= $selectedFile ?></span>
                </div>
            </div>

        </div>

        <?php if ((!empty($module->categorizationEnabled))
            && (!empty($module->categorizationModelsEnabled))): ?>
        <?php   if ($fileExist): ?>
        <div class="col-md-6">
            <div class="row m-b-10 m-t-15">
<!--                <div class="col-md-12">
                    <label>< ?= FileModule::t('amosattachments', 'Categoria') ?></label><br>
                    <span>< ?= $model->attachmentFile->getNomeCategoria() ?></span>
                </div>-->
            <?= $form->field($model->attachmentFile, 'attach_file_categorie_id')->widget(Select::class, [
                'data' => ArrayHelper::map(
                    AttachFileCategorie::find()->select(['id', 'titolo'])->asArray()->all(),
                    'id', 'titolo'),
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'options' => [
                    'multiple' => false,
                    'placeholder' => FileModule::t('amosattachments', 'Select...')
                ]
            ]); ?>
                
            </div>
        </div>
        <?php   else: ?>
        <div class="clearfix"></div>
        <div class="col-md-6">
            <?= $form->field(File::instance(), 'attach_file_categorie_id')->widget(Select::class, [
                'data' => ArrayHelper::map(
                    AttachFileCategorie::find()->select(['id', 'titolo'])->asArray()->all(),
                    'id', 'titolo'),
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'options' => [
                    'multiple' => false,
                    'placeholder' => FileModule::t('amosattachments', 'Select...')
                ]
            ]) ?>
        </div>
        <?php   endif; ?>

        <?php endif; ?>

        <div class="clearfix"></div>
        <div class="col-md-12">
            <div id="custom-tags-cont">
                <?= $form->field($model, 'customTags')->widget(Tagit::class, [
                    'options' => [
                        'id' => 'custom-tags-id',
                        'placeholder' => FileModule::t('amosattachments', 'Inserisci una parolachiave')
                    ],
                    'clientOptions' => [
                        'tagSource' => '/attachments/attach-databank-file/get-autocomplete-tag',
                        'autocomplete' => [
                            'delay' => 20,
                            'minLength' => 2,
                        ],
                    ]
                ])->label(FileModule::t('amosevents', 'Tag'))->hint(FileModule::t('amosattachments', "Inserisci delle etichette di codifica per rendere più semplice la ricerca all’interno della piattaforma.")); ?>
            </div>
        </div>
        <div class="col-md-12">
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

</div>