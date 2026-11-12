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
