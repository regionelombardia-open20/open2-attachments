<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-attachments/src/views
 */

use open20\amos\attachments\FileModule;
use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;

use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\attachments\models\search\AttachGalleryCategorySearch $model
 */

$this->title = FileModule::t('amosattachments', 'Categories');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/attachments']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attach-gallery-category-index">
    <?= $this->render('_search', [
        'model' => $model,
        'originAction' => Yii::$app->controller->action->id
    ]);
    ?>

    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                'name',
                'description:striptags',
                'default_order',
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                ],
            ],
        ],
    ]);
?>
</div>
