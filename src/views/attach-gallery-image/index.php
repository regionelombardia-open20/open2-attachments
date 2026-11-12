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

//da rimuovere dopo refactoring del controller
\open20\amos\attachments\utility\AttachmentsUtility::filterFilesDoesntExit($dataProvider);

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
//                'category_id',
//                'attachGalleryCategory' => [
//                    'attribute' => 'category',
//                    'format' => 'html',
//                    'label' => '',
//                    'value' => function ($model) {
//                        return strip_tags($model->category->name);
//                    }
//                ],
//                'gallery_id',
//                'attachGallery' => [
//                    'attribute' => 'gallery',
//                    'format' => 'html',
//                    'label' => '',
//                    'value' => function ($model) {
//                        return strip_tags($model->gallery->name);
//                    }
//                ],
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
