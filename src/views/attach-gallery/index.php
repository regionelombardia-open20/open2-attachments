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
 * @var open20\amos\attachments\models\search\AttachGallerySearch $model
 */

$this->title = \open20\amos\attachments\FileModule::t('amosattachments', 'Gallery');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/attachments']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attach-gallery-index">
    <?= $this->render('_search', ['model' => $model, 'originAction' => Yii::$app->controller->action->id]); ?>

    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                'slug',
                'name',
                'description:striptags',
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => '{view}{update}{delete}',
                    'buttons' => [
                            'delete' => function($url, $model){
                                if($model->slug == 'general'){
                                    return '';
                                }
                                return Html::a(\open20\amos\core\icons\AmosIcons::show('delete'), $url, [
                                    'class' => 'btn btn-danger-inverse',
                                    'data-confirm' => \open20\amos\attachments\FileModule::t('amosatachments','Sei sicuro di eliminare la galleria e tutte le immagini al suo interno?')]);
                            }
                    ]
                ],
            ],
        ],
    ]); ?>

</div>
