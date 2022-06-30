<?php

use kartik\select2\Select2;
use kartik\datecontrol\DateControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var \open20\amos\attachments\models\search\FileSearch $model
 * @var yii\widgets\ActiveForm $form
 */

$moduleTag = Yii::$app->getModule('tag');

$enableAutoOpenSearchPanel = isset(\Yii::$app->params['enableAutoOpenSearchPanel'])
    ? \Yii::$app->params['enableAutoOpenSearchPanel']
    : false;
?>

<div class="file-search" data-toggle-element="form-search">
    <div class="col-xs-12"><h2><?= Yii::t('app', 'Cerca per') ?>:</h2></div>

    <?php $form = ActiveForm::begin(
        [
            'action' => \Yii::$app->controller->action->id,
            'method' => 'get',
            'options' => [
                'id' => 'file_form_' . $model->id,
                'class' => 'form',
                'enctype' => 'multipart/form-data',
            ]
        ]
    );
    ?>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'name') ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?php
        $mq = \open20\amos\attachments\models\File::find();
        $mq->select('model');
        $mq->distinct();
        $mq->asArray();

        $models = $mq->all();
        ?>
        <?= $form->field($model, 'model')->widget(
            Select2::className(),
            [
                'data' => \yii\helpers\ArrayHelper::map($models,'model','model'),
                'options' => ['placeholder' => Yii::t('app', 'Cerca ...')],
                /*'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'ajax' => [
                        'url' => \yii\helpers\Url::to(['/admin/user-profile-ajax/ajax-user-list']),
                        'dataType' => 'json',
                        'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
                    ],
                ],*/
            ]
        );
        ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'attribute') ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?php
        $q = \open20\amos\admin\models\UserProfile::find();
        $q->select(new \yii\db\Expression('user_id, CONCAT(nome," ",cognome) as nomeCognome'));
        $q->asArray();

        $users = $q->all();
        ?>
        <?= $form->field($model, 'creator_id')->widget(
            Select2::className(),
            [
                'data' => \yii\helpers\ArrayHelper::map($users,'user_id','nomeCognome'),
                'options' => ['placeholder' => Yii::t('app', 'Cerca ...')],
                /*'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'ajax' => [
                        'url' => \yii\helpers\Url::to(['/admin/user-profile-ajax/ajax-user-list']),
                        'dataType' => 'json',
                        'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
                    ],
                ],*/
            ]
        );
        ?>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(
                Yii::t('app', 'Annulla'),
                [Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')],
                ['class' => 'btn btn-secondary']
            ) ?>
            <?= Html::submitButton(Yii::t('app', 'Cerca'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <!--a><p class="text-center">Ricerca avanzata<br>
        < ?=AmosIcons::show('caret-down-circle');?>
    </p></a-->

    <?php ActiveForm::end(); ?>

</div>
