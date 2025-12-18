<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos/attachments/views
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
                    ['num_max_char' => $model::MAX_LENGTH_TITLE])) ?>
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

