<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    package
 */

use open20\amos\attachments\FileModule;
use open20\amos\attachments\models\File;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\helpers\Html;

/** @var yii\web\View $this */
/** @var File $model */
/** @var string $goBackUrl */
/** @var array $categorie */
/** @var boolean $catIsValid */
/** @var string $nomeCat */

$this->title = FileModule::t('amosattachments', 'Aggiorna') . ' ' . $model->name;
////$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/attachments']];
//$this->params['breadcrumbs'][] = ['label' => FileModule::t('amosattachments',
//    'Categorie Allegati'), 'url' => ['index']];
////$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = FileModule::t('amosattachments', 'Aggiorna');
?>
<div class="attach-databank-file-form col-xs-12 nop">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>

    <?= Html::hiddenInput('goBackUrl', $goBackUrl); ?>

    <div class="row m-t-20">
        <div class="col-md-6">
            <div class="row m-b-10 m-t-15">
                <div class="col-md-12">
                    <label><?= FileModule::t('amosattachments', 'Nome del file') ?></label><br>
                    <span id="name-id-label"><?= $model->name . '.' . $model->type ?></span>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="row m-b-10 m-t-15">
<!--                <div class="col-md-12">
                    <label>< ?= FileModule::t('amosattachments', 'Categoria') ?></label><br>
                    <span>< ?= $model->attachmentFile->getNomeCategoria() ?></span>
                </div>-->
            <?= $form->field($model, 'attach_file_categorie_id')->widget(Select::class, [
                'data' => $categorie,
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'options' => [
                    'multiple' => false,
                    'placeholder' => FileModule::t('amosattachments', 'Select...')
                ]
            ])->label($model->getAttributeLabel('attach_file_categorie_id')
                . ((!$model->hasValidCategoria(true)) ? ' ' . html::tag('span', '', [
                    'class' => 'warning-cat text-danger mdi'
                        . ' mdi-alert-circle mdi-18px',
                    'title' => FileModule::t('amosattachments',
                        '#warning_category_list', [
                            'nome_categoria' => $model->getNomeCategoria(),
                            'nome_classe' => $model->model
                        ])
                ]) : '')); ?>

            </div>
        </div>

        <div class="clearfix"></div>

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
