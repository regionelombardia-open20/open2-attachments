<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-attachments/src/views 
 */
/**
* @var yii\web\View $this
* @var open20\amos\attachments\models\AttachGalleryCategory $model
*/

$this->title = Yii::t('amoscore', 'Aggiorna', [
    'modelClass' => 'Attach Gallery Category',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/attachments']];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('amoscore', 'Attach Gallery Category'),
    'url' => ['index']
];

$this->params['breadcrumbs'][] = Yii::t('amoscore', 'Aggiorna');
?>

<div class="attach-gallery-category-update">
<?= $this->render('_form', [
    'model' => $model,
    'fid' => null,
    'dataField' => null,
    'dataEntity' => null,
    ])
?>
</div>
