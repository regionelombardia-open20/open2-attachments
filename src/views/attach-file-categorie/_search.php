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

use open20\amos\attachments\FileModule;
use open20\amos\core\helpers\Html;
//use kartik\datecontrol\DateControl;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open20\amos\attachments\models\search\AttachFileCategorieSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div class="attach-file-categorie-search element-to-toggle" data-toggle-element="form-search">
    <?php $form = ActiveForm::begin([
        'action' => (isset($originAction) ? [$originAction] : ['index']),
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]); ?>

    <!-- id -->
    <?php // echo $form->field($model, 'id')  ?>

    <!-- titolo -->
    <div class="col-md-4">
        <?= $form->field($model, 'titolo')->textInput(['placeholder' => 'ricerca per titolo']) ?>
    </div>

    <!-- created_at -->
    <?php // echo $form->field($model, 'created_at')  ?>

    <!-- updated_at -->
    <?php // echo $form->field($model, 'updated_at')  ?>

    <!-- deleted_at -->
    <?php // echo $form->field($model, 'deleted_at')  ?>

    <!-- created_by -->
    <?php // echo $form->field($model, 'created_by')  ?>

    <!-- updated_by -->
    <?php // echo $form->field($model, 'updated_by')  ?>

    <!-- deleted_by -->
    <?php // echo $form->field($model, 'deleted_by')  ?>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(FileModule::t('amosattachments', 'Reset'),
                ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(FileModule::t('amosattachments', 'Search'),
                ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>
