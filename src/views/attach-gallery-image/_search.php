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
$module = \Yii::$app->getModule('attachments');
$disableFreeCropGallery = $module->disableFreeCropGallery;

$tagsImage = AttachGalleryImage::getTagIntereseInformativo();
$aspectRatio = [
    '1.7' => '16:9',
    '1' => '1:1',
];

if (!$disableFreeCropGallery) {
    $aspectRatio['other']= FileModule::t('amosattachments', 'Other');
}

$creator = '';
$manageUserIds = \Yii::$app->authManager->getUserIdsByRole('MANAGE_ATTACH_GALLERY');
$OperatorUserIds = \Yii::$app->authManager->getUserIdsByRole('ATTACH_IMAGE_REQUEST_OPERATOR');
$userIds = ArrayHelper::merge($manageUserIds, $OperatorUserIds);

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

<div class="attach-gallery-image-search element-to-toggle" data-toggle-element="form-search">
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['placeholder' => 'ricerca per nome']) ?>
        </div>

        <?php if ($tagsImage) : ?>
        <div class="col-md-6">
            <?= $form->field($model, 'tagsImageSearch')->widget(Select2::class, [
                'data' => ArrayHelper::map($tagsImage, 'id', 'nome'),
                'options' => [
                    'id' => 'tags-image-id',
                    'placeholder' => \Yii::t('app', "Seleziona i tag ..."),
                    'multiple' => true,
                    'title' => 'Tag di interesse informativo',
                ],
                'pluginOptions' => ['allowClear' => true]
            ])
            ->label(FileModule::t('amosattachments', "Tag di interesse informativo"));
            ?>
        </div>
        <?php endif; ?>

        <div class="col-md-6">
            <?= $form->field($model, 'customTagsSearch')->widget(Tagit::class, [
                'options' => [
                    'id' => 'custom-tags-id',
                    'placeholder' => FileModule::t('amosattachments', 'Inserisci una parolachiave')
                ],
                'clientOptions' => [
                    'tagSource' => '/attachments/attach-gallery-image/get-autocomplete-tag',
                    'autocomplete' => [
                        'delay' => 30,
                        'minLength' => 2,
                    ],
                ]
            ])
            ->label(FileModule::t('amosevents', 'Tag liberi'))
            ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'aspectRatioSearch')->widget(Select2::class, [
                'data' => $aspectRatio,
                'options' => [
                    'placeholder' => FileModule::t('amosattachments', 'Seleziona...'),
                ],
                'pluginOptions' => ['allowClear' => true]
                ])
                ->label('Aspect ratio');
            ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'created_at')->widget(DateControl::class, [
                'type' => DateControl::FORMAT_DATE
            ])
            ->label(FileModule::t('amosattachments', 'Creata a partire dal'))
            ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'created_by')->widget(Select2::class, [
                'data' => ArrayHelper::map($creatorsQuery->all(), 'id', 'nomeCognome'),
                'options' => [
                    'placeholder' => FileModule::t('amosattachments', 'Seleziona ...')
                ],
            ])
            ->label(FileModule::t('amosattachments', 'Creata da'));
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 m-b-10">
            <div class="pull-right">
                <?= Html::a(
                    Yii::t('amoscore', 'Reset'),
                    '/attachments/attach-gallery/single-gallery',
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
