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

/**
 * @var $query string
 * @var $model \open20\amos\attachments\models\Shutterstock
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\attachments\FileModule;
use open20\amos\attachments\models\Shutterstock;
use yii\helpers\Html;
?>

<?php
$form = ActiveForm::begin([
    'action' => (isset($originAction) ? [$originAction] : ['index']),
    'method' => 'get',
    'options' => [
        'class' => 'default-form'
    ]
]);

?>
<!--<div class="shutterstock-search element-to-toggle" data-toggle-element="form-search">-->
<div class="shutterstock-search">
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'query')
                ->textInput(['placeholder' => 'ricerca'])
                ->label(FileModule::t('amosattachments', 'Cerca immagine'))
            ?>
        </div>
        
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'image_type')->widget(\kartik\select2\Select2::className(), [
                'data' => Shutterstock::imageTypes(),
                'options' => ['placeholder' => 'Seleziona...'],
                'pluginOptions' => ['allowClear' => true]

            ])
                ->label(FileModule::t('amosattachments', 'Tipo di immagine'))
            ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'orientation')->widget(\kartik\select2\Select2::className(), [
                'data' => Shutterstock::orientationTypes(),
                'options' => ['placeholder' => 'Seleziona...'],
                'pluginOptions' => ['allowClear' => true]

            ])
                ->label(FileModule::t('amosattachments', 'orientamento'))
            ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'sort')->widget(\kartik\select2\Select2::className(), [
                'data' => Shutterstock::orderTypes(),
                'options' => ['placeholder' => 'Seleziona...'],
                'pluginOptions' => ['allowClear' => true]

            ])
                ->label(FileModule::t('amosattachments', 'Ordinamento'))
            ?>
        </div>
<!--        <div class="col-md-4">-->
<!--            --><?php //echo $form->field($model, 'color')->widget(\kartik\widgets\ColorInput::className(), [
//                'options' => ['placeholder' => 'Seleziona...'],
//
//            ])
//                ->label(FileModule::t('amosattachments', 'Colore'))
//            ?>
<!--        </div>-->

    </div>



    <div class="row">
        <div class="col-xs-12 m-b-10">
            <div class="pull-right">
                <?= Html::a(
                    Yii::t('amoscore', 'Reset'),
                    '/attachments/shutterstock/index',
                    [
                        'class' => 'btn btn-secondary'
                    ]
                )
                ?>
                <?= Html::submitButton(
                    Yii::t('amoscore', 'Search'),
                    [
                        'class' => 'btn btn-navigation-primary'
                    ]
                )
                ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
