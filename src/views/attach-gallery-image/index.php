<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-attachments/src/views
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\attachments\models\search\AttachGalleryImageSearch $model
 */

$this->title = Yii::t('amoscore', 'Attach Gallery Image');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/attachments']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attach-gallery-image-index">
    <?= $this->render('_search', [
        'model' => $model,
        'originAction' => Yii::$app->controller->action->id]
    );
    ?>

    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                'category_id',
                'attachGalleryCategory' => [
                    'attribute' => 'category',
                    'format' => 'html',
                    'label' => '',
                    'value' => function ($model) {
                        return strip_tags($model->category->name);
                    }
                ],
                'gallery_id',
                'attachGallery' => [
                    'attribute' => 'gallery',
                    'format' => 'html',
                    'label' => '',
                    'value' => function ($model) {
                        return strip_tags($model->gallery->name);
                    }
                ],
                'name',
                'description:striptags',
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                ],
            ],
        ],
    ]);
?>
</div>
