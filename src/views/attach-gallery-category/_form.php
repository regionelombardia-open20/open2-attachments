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
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\icons\AmosIcons;

use kartik\datecontrol\DateControl;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\redactor\widgets\Redactor;
use yii\helpers\Inflector;

/**
* @var yii\web\View $this
* @var open20\amos\attachments\models\AttachGalleryCategory $model
* @var yii\widgets\ActiveForm $form
*/

$form = ActiveForm::begin([
    'options' => [
        'id' => 'attach-gallery-category_' . ((isset($fid))? $fid : 0),
        'data-fid' => (isset($fid))? $fid : 0,
        'data-field' => ((isset($dataField))? $dataField : ''),
        'data-entity' => ((isset($dataEntity))? $dataEntity : ''),
        'class' => ((isset($class))? $class : '')
    ]
]);
?>
<div class="attach-gallery-category-form col-xs-12 nop">
    <div class="row">
        <div class="col-xs-12">
            <h2 class="subtitle-form">Settings</h2>
            <div class="col-md-8 col xs-12">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'description')->widget(Redactor::class, [
                'options' => [
                    'id' => 'description' . $fid,
                ],
                'clientOptions' => [
                    'language' => substr(Yii::$app->language, 0, 2),
                    'plugins' => ['clips', 'fontcolor', 'imagemanager'],
                    'buttons' => [
                        'format', 'bold', 'italic', 'deleted', 'lists', 'image',
                        'file', 'link', 'horizontalrule'
                    ],
                ],
            ]);
            ?>
			
            <?= $form->field($model, 'default_order')->textInput() ?>
            
            <?= RequiredFieldsTipWidget::widget(); ?>
            <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
            </div>
                
            <div class="col-md-4 col xs-12"></div>
        </div>
            
        <div class="clearfix"></div> 

    </div>
    
</div>

<?php ActiveForm::end(); ?>
