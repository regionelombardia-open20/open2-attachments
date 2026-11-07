<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\file
 * @category   CategoryName
 */

use open20\amos\attachments\models\File;
use open20\amos\admin\models\UserProfile;

use kartik\select2\Select2;
use kartik\datecontrol\DateControl;

use yii\helpers\ArrayHelper;       
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

$mq = File::find();
$mq->select('model')
    ->distinct()
    ->asArray();

$models = $mq->all();       

$q = UserProfile::find();
$q->select(new \yii\db\Expression('user_id, CONCAT(nome," ",cognome) as nomeCognome'))
    ->asArray();

$users = $q->all();
?>

<div class="file-search" data-toggle-element="form-search">
    <div class="col-xs-12">
        <h2><?= Yii::t('app', 'Cerca per') ?>:</h2>
    </div>

    <?php $form = ActiveForm::begin([
        'action' => \Yii::$app->controller->action->id,
        'method' => 'get',
        'options' => [
            'id' => 'file_form_' . $model->id,
            'class' => 'form',
            'enctype' => 'multipart/form-data',
        ]
    ]);
    ?>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'name') ?>
    </div>

    <div class="col-sm-6 col-lg-4">
    <?= $form->field($model, 'model')->widget(Select2::class, [
        'data' => ArrayHelper::map(
            $models,
            'model',
            'model'
        ),
        'options' => ['placeholder' => Yii::t('app', 'Cerca ...')],
    ]);
    ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'attribute') ?>
    </div>

    <div class="col-sm-6 col-lg-4">
    <?= $form->field($model, 'creator_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(
            $users,
            'user_id',
            'nomeCognome'
        ),
        'options' => ['placeholder' => Yii::t('app', 'Cerca ...')],
    ]);
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

    <?php ActiveForm::end(); ?>

</div>
