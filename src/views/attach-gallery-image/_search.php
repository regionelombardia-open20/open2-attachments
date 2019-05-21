<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/lispa/amos-attachments/src/views
 */

use lispa\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var lispa\amos\attachments\models\search\AttachGalleryImageSearch $model
 * @var yii\widgets\ActiveForm $form
 */


?>
<div class="attach-gallery-image-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
        'action' => (isset($originAction) ? [$originAction] : ['index']),
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>

    <!-- id --> <?php // echo $form->field($model, 'id') ?>

    <!-- category_id -->
    <div class="col-md-4"> <?=
        $form->field($model, 'category_id')->textInput(['placeholder' => 'ricerca per category id']) ?>

    </div>


<!--    <div class="col-md-4">-->
<!--        --><?php //echo
//        $form->field($model, 'attachGalleryCategory')->textInput(['placeholder' => 'ricerca per '])->label('');
//        ?>
<!--    </div>-->
    <!-- gallery_id -->
    <div class="col-md-4"> <?=
        $form->field($model, 'gallery_id')->textInput(['placeholder' => 'ricerca per gallery id']) ?>

    </div>


<!--    <div class="col-md-4">-->
<!--        --><?php //echo
//        $form->field($model, 'attachGallery')->textInput(['placeholder' => 'ricerca per '])->label('');
//        ?>
<!--    </div>-->
    <!-- name -->
    <div class="col-md-4"> <?=
        $form->field($model, 'name')->textInput(['placeholder' => 'ricerca per name']) ?>

    </div>

    <!-- description -->
    <div class="col-md-4"> <?=
        $form->field($model, 'description')->textInput(['placeholder' => 'ricerca per description']) ?>

    </div>

    <!-- created_at --> <?php // echo $form->field($model, 'created_at') ?>

    <!-- updated_at --> <?php // echo $form->field($model, 'updated_at') ?>

    <!-- deleted_at --> <?php // echo $form->field($model, 'deleted_at') ?>

    <!-- created_by --> <?php // echo $form->field($model, 'created_by') ?>

    <!-- updated_by --> <?php // echo $form->field($model, 'updated_by') ?>

    <!-- deleted_by --> <?php // echo $form->field($model, 'deleted_by') ?>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(Yii::t('amoscore', 'Reset'), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Yii::t('amoscore', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>
