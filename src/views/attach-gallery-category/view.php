<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-attachments/src/views 
 */

use kartik\datecontrol\DateControl;

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/**
* @var yii\web\View $this
* @var open20\amos\attachments\models\AttachGalleryCategory $model
*/

$this->title = strip_tags($model);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/attachments']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Attach Gallery Category'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="attach-gallery-category-view">
<?= DetailView::widget([
    'model' => $model,    
    'attributes' => [
        'name',
        'description:html',
        'default_order',
    ],    
]) ?>
</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
<?= Html::a(
    Yii::t('amoscore', 'Chiudi'),
    Url::previous(), [
        'class' => 'btn btn-secondary'
    ]);
?>
</div>
