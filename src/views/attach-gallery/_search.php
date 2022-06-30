<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-attachments/src/views 
 */

use open20\amos\core\helpers\Html;

use kartik\datecontrol\DateControl;

use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var open20\amos\attachments\models\search\AttachGallerySearch $model
* @var yii\widgets\ActiveForm $form
*/
$form = ActiveForm::begin([
    'action' => (isset($originAction) ? [$originAction] : ['index']),
    'method' => 'get',
    'options' => [
        'class' => 'default-form'
    ]
]);
?>

<div class="attach-gallery-search element-to-toggle" data-toggle-element="form-search">
    <div class="col-md-4">
        <?= $form->field($model, 'slug')->textInput(['placeholder' => 'ricerca per slug' ]) ?>
    </div> 

    <div class="col-md-4">
        <?= $form->field($model, 'name')->textInput(['placeholder' => 'ricerca per name' ]) ?>
    </div> 

    <div class="col-md-4">
        <?= $form->field($model, 'description')->textInput([
            'placeholder' => 'ricerca per description'
        ]) ?>
    </div> 

    <div class="col-xs-12">
        <div class="pull-right">
        <?= Html::resetButton(Yii::t('amoscore', 'Reset'), ['class' => 'btn btn-secondary']) ?>
        <?= Html::submitButton(Yii::t('amoscore', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

</div>
<?php ActiveForm::end(); ?>
