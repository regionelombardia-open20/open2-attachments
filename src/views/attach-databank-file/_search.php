<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-attachments/src/views
 */

use open20\amos\admin\models\UserProfile;
use open20\amos\attachments\FileModule;
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
                ->label(FileModule::t('amosattachments', 'Creata a partire dal'))
            ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'created_by')->widget(Select2::class, [
                'data' => ArrayHelper::map($creatorsQuery->all(), 'id', 'nomeCognome'),
                'options' => [
                    'placeholder' => FileModule::t('amosattachments', 'Seleziona ...')
                ],
            ])
                ->label(FileModule::t('amosattachments', 'Creata da'));
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
