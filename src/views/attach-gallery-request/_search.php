<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-attachments/src/views 
 */
use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/**
* @var yii\web\View $this
* @var open20\amos\attachments\models\search\AttachGalleryRequestSearch $model
* @var yii\widgets\ActiveForm $form
*/


?>
<div class="attach-gallery-request-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
    'action' => (isset($originAction) ? [$originAction] : ['index']),
    'method' => 'get',
    'options' => [
    'class' => 'default-form'
    ]
    ]);
    ?>

    <!-- id -->  <?php // echo $form->field($model, 'id') ?>

 <!-- title -->
<div class="col-md-4"> <?= 
$form->field($model, 'title')->textInput(['placeholder' => 'ricerca per title' ]) ?>

 </div> 

<!-- status -->
<div class="col-md-4"> <?= 
$form->field($model, 'status')->textInput(['placeholder' => 'ricerca per status' ]) ?>

 </div> 

<!-- aspect_ratio -->
<div class="col-md-4"> <?= 
$form->field($model, 'aspect_ratio')->textInput(['placeholder' => 'ricerca per aspect ratio' ]) ?>

 </div> 

<!-- text_request -->
<div class="col-md-4"> <?= 
$form->field($model, 'text_request')->textInput(['placeholder' => 'ricerca per text request' ]) ?>

 </div> 

<!-- text_reply -->
<div class="col-md-4"> <?= 
$form->field($model, 'text_reply')->textInput(['placeholder' => 'ricerca per text reply' ]) ?>

 </div> 

<!-- attach_gallery_image_id -->
<div class="col-md-4"> <?= 
$form->field($model, 'attach_gallery_image_id')->textInput(['placeholder' => 'ricerca per attach gallery image id' ]) ?>

 </div> 

<!-- created_at -->  <?php // echo $form->field($model, 'created_at') ?>

 <!-- updated_at -->  <?php // echo $form->field($model, 'updated_at') ?>

 <!-- deleted_at -->  <?php // echo $form->field($model, 'deleted_at') ?>

 <!-- created_by -->  <?php // echo $form->field($model, 'created_by') ?>

 <!-- updated_by -->  <?php // echo $form->field($model, 'updated_by') ?>

 <!-- deleted_by -->  <?php // echo $form->field($model, 'deleted_by') ?>

     <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(Yii::t('amoscore', 'Reset'), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Yii::t('amoscore', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>
