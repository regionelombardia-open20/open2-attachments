<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos/attachments/views 
 */

use open20\amos\attachments\FileModule;
//use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use Yii;
//use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var open20\amos\attachments\models\search\AttachFileCategorieSearch $model */

$this->title = FileModule::t('amosattachments', 'Categorie Allegati');
//$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/attachments']];
$this->params['breadcrumbs'][] = [
    'label' => FileModule::t('amosattachments', 'Asset Allegati'),
    'url' => ['/attachments/attach-databank-file']
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attach-file-categorie-index">
    <?= $this->render('_search', [
        'model' => $model,
        'originAction' => Yii::$app->controller->action->id]); ?>

    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
//                ['class' => 'yii\grid\SerialColumn'],
                'titolo',
                [
                    'label' => $model->getAttributeLabel('assets'),
                    'format' => 'html',
                    'value' => function ($model) {
                        /** @var AttachFileCategorieSearch $model */
                        $assetNames = $model->getAssetNames();
                        if (empty($assetNames)) {
                            return null;
                        }
                        $res = '<ul>' . PHP_EOL;
                        foreach ($assetNames as $assetName) {
                            $res .= '<li>' . $assetName . '</li>';
                        }
                        $res .= '</ul>' . PHP_EOL;
                        return $res;
                    }
                ],
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
//                    'template' => '{view}{update}{delete}',
                    'template' => '{update}{delete}',
                ],
            ],
        ],
        /* 'listView' => [
          'itemView' => '_item',
          'masonry' => FALSE,

          // Se masonry settato a TRUE decommentare e settare i parametri seguenti
          // nel CSS settare i seguenti parametri necessari al funzionamento tipo
          // .grid-sizer, .grid-item {width: 50&;}
          // Per i dettagli recarsi sul sito http://masonry.desandro.com

          //'masonrySelector' => '.grid',
          //'masonryOptions' => [
          //    'itemSelector' => '.grid-item',
          //    'columnWidth' => '.grid-sizer',
          //    'percentPosition' => 'true',
          //    'gutter' => '20'
          //]
          ],
          'iconView' => [
          'itemView' => '_icon'
          ],
          'mapView' => [
          'itemView' => '_map',
          'markerConfig' => [
          'lat' => 'domicilio_lat',
          'lng' => 'domicilio_lon',
          'icon' => 'iconMarker',
          ]
          ],
          'calendarView' => [
          'itemView' => '_calendar',
          'clientOptions' => [
          //'lang'=> 'de'
          ],
          'eventConfig' => [
          //'title' => 'titleEvent',
          //'start' => 'data_inizio',
          //'end' => 'data_fine',
          //'color' => 'colorEvent',
          //'url' => 'urlEvent'
          ],
          'array' => false,//se ci sono più eventi legati al singolo record
          //'getEventi' => 'getEvents'//funzione da abilitare e implementare nel model per creare un array di eventi legati al record
          ] */
    ]); ?>
</div>
