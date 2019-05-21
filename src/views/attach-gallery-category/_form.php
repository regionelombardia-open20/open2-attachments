<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/lispa/amos-attachments/src/views 
 */
use lispa\amos\core\helpers\Html;
use lispa\amos\core\forms\ActiveForm;
use kartik\datecontrol\DateControl;
use lispa\amos\core\forms\Tabs;
use lispa\amos\core\forms\CloseSaveButtonWidget;
use lispa\amos\core\forms\RequiredFieldsTipWidget;
use yii\helpers\Url;
use lispa\amos\core\forms\editors\Select;
use yii\helpers\ArrayHelper;
use lispa\amos\core\icons\AmosIcons;
use yii\bootstrap\Modal;
use yii\redactor\widgets\Redactor;
use yii\helpers\Inflector;

/**
* @var yii\web\View $this
* @var lispa\amos\attachments\models\AttachGalleryCategory $model
* @var yii\widgets\ActiveForm $form
*/


 ?>
<div class="attach-gallery-category-form col-xs-12 nop">

    <?php     $form = ActiveForm::begin([
    'options' => [
    'id' => 'attach-gallery-category_' . ((isset($fid))? $fid : 0),
    'data-fid' => (isset($fid))? $fid : 0,
    'data-field' => ((isset($dataField))? $dataField : ''),
    'data-entity' => ((isset($dataEntity))? $dataEntity : ''),
    'class' => ((isset($class))? $class : '')
    ]
    ]);
     ?>
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>
    
        <div class="row"><div class="col-xs-12"><h2 class="subtitle-form">Settings</h2><div class="col-md-8 col xs-12"><!-- name string -->
			<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?><!-- description text -->
			<?= $form->field($model, 'description')->widget(yii\redactor\widgets\Redactor::className(), [
                            				'options' => [
                            					'id' => 'description' . $fid,
                            					],
                            				'clientOptions' => [
                            				'language' => substr(Yii::$app->language, 0, 2),
                            				'plugins' => ['clips', 'fontcolor', 'imagemanager'],
                            				'buttons' => ['format', 'bold', 'italic', 'deleted', 'lists', 'image', 'file', 'link', 'horizontalrule'],
                            				],
                			]);
                			?><!-- default_order integer -->
			<?= $form->field($model, 'default_order')->textInput() ?><?= RequiredFieldsTipWidget::widget(); ?><?= CloseSaveButtonWidget::widget(['model' => $model]); ?><?php ActiveForm::end(); ?></div><div class="col-md-4 col xs-12"></div></div><div class="clearfix"></div> 

</div>
</div>
