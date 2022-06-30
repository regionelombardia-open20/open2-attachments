<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\file
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\core\views\DataProviderView;
use open20\amos\events\AmosEvents;

use yii\widgets\ActiveForm;
use yii\web\View;

/**
 * @var yii\web\View $this
 * @var \open20\amos\attachments\models\search\FileSearch $model
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var string $currentView
 */

$form = ActiveForm::begin([
    'action' => \Yii::$app->controller->action->id,
    'method' => 'post',
    ]
);
?>
<div class="event-index">
    <div class="col-md-12">
        <label><?= Yii::t('app', 'Query')?></label>
        <?= Html::textarea('query', '', ['class'=> 'form-control', 'width' => "100%",'rows' => 20]); ?>
    </div>
    <div>
        <?= Html::submitButton('Conferma', ['class' => 'btn btn-primary']); ?>
    </div>

</div>
<?php ActiveForm::end(); ?>
