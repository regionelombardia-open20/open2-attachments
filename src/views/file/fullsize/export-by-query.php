<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\events\views\file
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
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
