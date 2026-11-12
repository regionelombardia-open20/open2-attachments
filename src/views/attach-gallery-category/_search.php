<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-attachments
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

use kartik\datecontrol\DateControl;

use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var open20\amos\attachments\models\search\AttachGalleryCategorySearch $model
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

<div class="attach-gallery-category-search element-to-toggle" data-toggle-element="form-search">
    <div class="col-md-4">
        <?= $form->field($model, 'name')->textInput(['placeholder' => 'ricerca per name' ]) ?>
    </div> 

    <div class="col-md-4">
        <?= $form->field($model, 'description')->textInput([
            'placeholder' => 'ricerca per description'
        ])
        ?>
    </div> 

    <div class="col-md-4">
        <?= $form->field($model, 'default_order')->textInput([
            'placeholder' => 'ricerca per default order'
        ])
        ?>
    </div> 

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(Yii::t('amoscore', 'Reset'), [
                'class' => 'btn btn-secondary'
            ])
            ?>
            
            <?= Html::submitButton(Yii::t('amoscore', 'Search'), [
                'class' => 'btn btn-navigation-primary'
            ])
        ?>
        </div>
    </div>

    <div class="clearfix"></div>

</div>
<?php ActiveForm::end(); ?>
