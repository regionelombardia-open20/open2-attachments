<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-attachments
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
 */

use open20\amos\attachments\FileModule;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\DataProviderView;

use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\attachments\models\search\AttachGallerySearch $model
 */

$this->title = FileModule::t('amosattachments', 'Gallery');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['attachments/attach-gallery/single-gallery']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attach-gallery-index">
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
                'slug',
                'name',
                'description:striptags',
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => '{view}{update}{delete}',
                    'buttons' => [
                        'delete' => function($url, $model) {
                            if ($model->slug == 'general') {
                                return '';
                            }
                                
                            return Html::a(
                                AmosIcons::show('delete'),
                                $url,
                                [
                                    'class' => 'btn btn-danger-inverse',
                                    'data-confirm' => FileModule::t(
                                        'amosatachments',
                                        'Sei sicuro di eliminare la galleria e tutte le immagini al suo interno?'
                                    )
                                ]
                            );
                        }
                    ]
                ],
            ],
        ],
    ]);
 ?>
</div>
