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
use open20\amos\core\forms\TextEditorWidget;

/**
 * @var yii\web\View $this
 * @var open20\amos\attachments\models\AttachGallery $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div class="attach-gallery-form col-xs-12 nop">

    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>

    <?php if ($model->isNewRecord && $model->slug != 'general') { ?>
        <?php
        $form = ActiveForm::begin([
                'options' => [
                    'id' => 'attach-gallery_'.((isset($fid)) ? $fid : 0),
                    'data-fid' => (isset($fid)) ? $fid : 0,
                    'data-field' => ((isset($dataField)) ? $dataField : ''),
                    'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
                    'class' => ((isset($class)) ? $class : '')
                ]
        ]);
        ?>
        <div class="row">
            <div class="col-xs-12">
                <h2 class="subtitle-form"><?= FileModule::t('amosattachments', "#settings") ?></h2>
                <div class="row">

                    <div class="col-md-12 col xs-12"><!-- name string -->
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?><!-- description text -->
                        <?=
                        $form->field($model, 'description')->widget(TextEditorWidget::className(),
                            [
                            'options' => [
                                'id' => 'description'.$fid,
                            ],
                            'clientOptions' => [
                                'placeholder' => FileModule::t('amosattachments', '#description_field_placeholder'),
                                'lang' => substr(Yii::$app->language, 0, 2)
                            ]
                        ]);
                        ?>
                        <?php /* $form->field($model, 'description')->widget(yii\redactor\widgets\Redactor::className(), [
                        'options' => [
                        'id' => 'description' . $fid,
                        ],
                        'clientOptions' => [
                        'language' => substr(Yii::$app->language, 0, 2),
                        'plugins' => ['clips', 'fontcolor', 'imagemanager'],
                        'buttons' => ['format', 'bold', 'italic', 'deleted', 'lists', 'image', 'file', 'link', 'horizontalrule'],
                        ],
                        ]); */
                        ?><?= RequiredFieldsTipWidget::widget(); ?>

                        <?= CloseSaveButtonWidget::widget(['model' => $model]); ?><?php ActiveForm::end(); ?></div>
                    <div class="col-md-4 col xs-12"></div>
                </div>
                
            </div>
        </div>
    <?php } ?>

    <div class="row">
        <div class="col-xs-12">
            <h2 class="subtitle-form"><?= FileModule::t('amosattachments', '#images') ?></h2>
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                    <?php if (!$model->isNewRecord) { ?>
                        <!--< ?=
                        Html::a(FileModule::t('amosattachments', '#new_image'),
                            ['/attachments/attach-gallery-image/create', 'id' => $model->id],
                            ['class' => 'btn btn-navigation-primary'])
                        ?>-->
                        <?php
                        echo \open20\amos\core\views\AmosGridView::widget([
                            'dataProvider' => $dataProviderImages,
                            'columns' => [
                                'image' => [
                                    'label' => FileModule::t('amosattachments', 'Image'),
                                    'format' => 'html',
                                    'value' => function ($model) {
                                        $url = '';
                                        if ($model->attachImage) {
                                            $url = $model->attachImage->getUrl('square_small');
                                        }
                                        return \open20\amos\core\helpers\Html::img($url,
                                                [
                                                'class' => 'gridview-image',
                                                'alt' => FileModule::t('amosattachments', 'Image')
                                        ]);
                                    }
                                ],
                                //'name',
                                [
                                    'attribute' => 'category.name',
                                    'label' => FileModule::t('amosattachments', "Category")
                                ],
                                [
                                    'class' => \open20\amos\core\views\grid\ActionColumn::className(),
                                    'controller' => 'attach-gallery-image',
                                ]
                            ]
                        ]);
                        ?>
                        <?php
                    } else {
                        echo "<p>".FileModule::t('amosattachments',
                            "E' necessario salvare per poter inserire delle immagini alla galleria")."</p>";
                    }
                    ?>
                </div>
            </div>
            
        </div>
    </div>
    <div class="clearfix"></div>


</div>
