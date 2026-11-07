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
* @var open20\amos\attachments\models\search\AttachGalleryRequestSearch $model
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
<div class="attach-gallery-request-search element-to-toggle" data-toggle-element="form-search">
    <div class="col-md-4">
        <?= $form->field($model, 'title')->textInput(['placeholder' => 'ricerca per title' ]) ?>
    </div> 

    <div class="col-md-4">
        <?= $form->field($model, 'status')->textInput(['placeholder' => 'ricerca per status' ]) ?>
    </div> 

    <div class="col-md-4">
        <?= $form->field($model, 'aspect_ratio')->textInput(['placeholder' => 'ricerca per aspect ratio' ]) ?>
    </div> 

    <div class="col-md-4">
        <?= $form->field($model, 'text_request')->textInput(['placeholder' => 'ricerca per text request' ]) ?>
    </div> 

    <div class="col-md-4">
        <?= $form->field($model, 'text_reply')->textInput(['placeholder' => 'ricerca per text reply' ]) ?>
    </div> 

    <div class="col-md-4">
        <?= $form->field($model, 'attach_gallery_image_id')->textInput(['placeholder' => 'ricerca per attach gallery image id' ]) ?>
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
