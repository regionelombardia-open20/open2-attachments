<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/lispa/amos-attachments/src/views
 */

use lispa\amos\core\helpers\Html;
use lispa\amos\core\views\DataProviderView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var lispa\amos\attachments\models\search\AttachGallerySearch $model
 */

$this->title = \lispa\amos\attachments\FileModule::t('amosattachments', 'Gallery');
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
                    'class' => 'lispa\amos\core\views\grid\ActionColumn',
                    'template' => '{view}{update}{delete}',
                    'buttons' => [
                            'delete' => function($url, $model){
                                if($model->slug == 'general'){
                                    return '';
                                }
                                return Html::a(\lispa\amos\core\icons\AmosIcons::show('delete'), $url, [
                                    'class' => 'btn btn-danger-inverse',
                                    'data-confirm' => \lispa\amos\attachments\FileModule::t('amosatachments','Sei sicuro di eliminare la galleria e tutte le immagini al suo interno?')]);
                            }
                    ]
                ],
            ],
        ],
    ]); ?>

</div>
