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

use open20\amos\admin\models\UserProfile;
use open20\amos\attachments\FileModule;
use open20\amos\attachments\models\AttachFileCategorie;
use open20\amos\attachments\models\AttachGalleryImage;
use open20\amos\core\helpers\Html;

use kartik\datecontrol\DateControl;
use kartik\select2\Select2;

use xj\tagit\Tagit;

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open20\amos\attachments\models\search\AttachGalleryImageSearch $model
 * @var yii\widgets\ActiveForm $form
 */

$js = <<<JS
    $('.btn-tools-container div[data-toggle-element="form-search"]').click(function(){
        $('#search-form-gallery').toggle();
    });
JS;
$this->registerJs($js);

$module = \Yii::$app->getModule('attachments');

$creator = '';
$manageUserIds = \Yii::$app->authManager->getUserIdsByRole('ATTACH_DATABANK_FILE_ADMINISTRATOR');
$userIds = ArrayHelper::merge($manageUserIds, []);

$creatorsQuery = UserProfile::find()
    ->andWhere(['user_id' => $userIds])
    ->andWhere(['NOT LIKE', 'nome', '########'])
    ->andWhere(['attivo' => 1])
    ->orderBy('nome, cognome ASC');

$form = ActiveForm::begin([
    'action' => (isset($originAction) ? [$originAction] : ['index']),
    'method' => 'get',
    'options' => [
        'class' => 'default-form'
    ]
]);
?>

<div id="search-form-gallery" class="attach-databank-file-search search-form-class-attachments" style="display:none">
    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'name')->textInput(['placeholder' => 'ricerca per nome']) ?>
        </div>


        <div class="col-md-4">
            <?= $form->field($model, 'extension')->textInput(['placeholder' => 'ricerca per estensione']) ?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'uploadData')->widget(DateControl::class, [
                'type' => DateControl::FORMAT_DATE
            ])
                ->label(FileModule::t('amosattachments', 'Caricato a partire dal'))
            ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'created_by')->widget(Select2::class, [
                'data' => ArrayHelper::map($creatorsQuery->all(), 'id', 'nomeCognome'),
                'options' => [
                    'placeholder' => FileModule::t('amosattachments', 'Seleziona ...')
                ],
            ])
                ->label(FileModule::t('amosattachments', 'Creato da'));
            ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'customTagsSearch')->widget(Tagit::class, [
                'options' => [
                    'id' => 'custom-tags-id',
                    'placeholder' => FileModule::t('amosattachments', 'Inserisci una parolachiave')
                ],
                'clientOptions' => [
                    'tagSource' => '/attachments/attach-databank-file/get-autocomplete-tag',
                    'autocomplete' => [
                        'delay' => 30,
                        'minLength' => 2,
                    ],
                ]
            ])
                ->label(FileModule::t('amosevents', 'Tag liberi'))
            ?>
        </div>
        
        <?php if ((!empty($module->categorizationEnabled))
            && (!empty($module->categorizationModelsEnabled))): ?>
        <div class="clearfix"></div>
        <div class="col-md-4">
            <?= $form->field($model, 'attachmentCatSearch')->widget(Select2::class, [
                'data' => ArrayHelper::map(AttachFileCategorie::find()
                    ->select(['id', 'titolo'])->asArray()->all(), 'id', 'titolo'),
                'options' => [
                    'placeholder' => FileModule::t('amosattachments', 'Seleziona ...')
                ],
            ]);
            ?>
        </div>
        <?php endif; ?>

    </div>

    <div class="row">
        <div class="col-xs-12 m-b-10">
            <div class="pull-right">
                <?= Html::a(
                    Yii::t('amoscore', 'Reset'),
                    '/attachments/attach-databank-file/index',
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
