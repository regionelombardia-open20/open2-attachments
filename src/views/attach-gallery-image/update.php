<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-attachments/src/views 
 */

use  open20\amos\attachments\FileModule;

/**
* @var yii\web\View $this
* @var open20\amos\attachments\models\AttachGalleryImage $model
*/

$this->title = FileModule::t('amosattachments', 'Update image')
    . " '"
    . $model->name
    . "'";
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/attachments']];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('amoscore', 'Attach Gallery Image'),
    'url' => ['index']
];

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="attach-gallery-image-update">
<?= $this->render('_form', [
    'model' => $model,
    'fid' => null,
    'dataField' => null,
    'dataEntity' => null,
])
?>
</div>
