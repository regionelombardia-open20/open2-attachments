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
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
//use open20\amos\core\forms\Tabs;
//use open20\amos\core\forms\TextEditorWidget;
use open20\amos\core\forms\editors\Select;
//use open20\amos\core\helpers\Html;
//use open20\amos\core\icons\AmosIcons;
use kartik\select2\Select2;
//use kartik\datecontrol\DateControl;
//use open20\design\components\bootstrapitalia\Select;
//use Yii;
//use yii\bootstrap\Modal;
//use yii\helpers\Url;
//use yii\helpers\ArrayHelper;
//use yii\helpers\Inflector;
use xj\tagit\Tagit;

/** @var yii\web\View $this */
/** @var open20\amos\attachments\models\AttachFileCategorie $model */
/** @var yii\widgets\ActiveForm $form */
?>
<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data']
]); ?>
<div class="attach-file-categorie-form col-xs-12 nop">

    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>

    <div class="row">
        <div class="col-lg-6 col-sm-12">
            <!-- titolo string -->
            <?= $form->field($model, 'titolo')->textInput(['maxlength' => true])
                ->hint(FileModule::t('amosattachments', '#max_char_category_title_hint',
                    ['num_max_char' => FileModule::instance()->attachFileCategorieTitleMaxLength])) ?>
        </div>
        <div class="col-lg-6 col-sm-12">
            <!-- assetClassesForm string -->
            <?= $form->field($model, 'assetClassesForm')->widget(Select::class, [
                'data' => $model::getDateForAssetMenu(),
                'options' => [
                    'multiple' => true,
                    'placeholder' => FileModule::t('amosattachments', 'Select...')
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'pluginEvents' => [
                    'krajeeselect2:selectall' => "function(e) {
                        $(e.target).find('option').each(function () {
                            let flag = true;
                            let opt = $(this);
                            if (!opt.attr('disabled') && opt.val().length) {
                                opt.prop('selected', flag);
                            }
                        });
                    }",
                    'krajeeselect2:unselectall' => "function(e) {
                        $(e.target).find('option').each(function () {
                            let flag = false;
                            let opt = $(this);
                            if (!opt.attr('disabled') && opt.val().length) {
                                opt.prop('selected', flag);
                            }
                        });
                    }",
                ],
//                'items' => $model::getDateForAssetMenu(),
            ])->hint(FileModule::t('amosattachments', 'Se non verrà impostato alcun asset verranno associati tutti')) ?>
        </div>
    </div>
    <div class="clearfix"></div>
    
    <?= RequiredFieldsTipWidget::widget(); ?>
    <?= CreatedUpdatedWidget::widget(['model' => $model]) ?>
    <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
</div>
<?php ActiveForm::end(); ?>

