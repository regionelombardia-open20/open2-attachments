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

use open20\amos\admin\models\UserProfile;
use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use open20\amos\attachments\FileModule;
use open20\amos\attachments\models\AttachGalleryRequest;

use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\attachments\models\search\AttachGalleryRequestSearch $model
 *
 */

$this->title = Yii::t('amoscore', 'Richieste Databank immagini');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/attachments']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attach-gallery-request-index">
    <?= $this->render('_search', ['model' => $model, 'originAction' => Yii::$app->controller->action->id]); ?>

    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                [
                    'label' => FileModule::t('amosattachments', 'ID Richiesta'),
                    'attribute' => 'id'
                ],
                [
                    'label' => FileModule::t('amosattachments', 'Image title'),
                    'attribute' => 'title'
                ],
                [
                    'attribute' => 'created_by',
                    'label' => FileModule::t('amosattachments', "Created by"),
                    'value' => function ($model) {
                        $profile = UserProfile::find()->andWhere(['user_id' => $model->created_by])->one();
                        if ($profile) {
                            return $profile->nomeCognome;
                        }
                        return '-';
                    },
                ],
                [
                    'label' => FileModule::t('amosattachments', 'Created at'),
                    'attribute' => 'created_at',
                    'format' => 'date'
                ],
                [
                    'label' => FileModule::t('amosattachments', 'Status'),
                    'attribute' => 'workflowStatus.label'
                ],
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => '{view}{update}',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            if ($model->status == AttachGalleryRequest::IMAGE_REQUEST_WORKFLOW_STATUS_OPENED
                                && \Yii::$app->user->can('ATTACH_IMAGE_REQUEST_OPERATOR')) {
                                return \yii\helpers\Html::a(\open20\amos\core\icons\AmosIcons::show('edit'), $url, [
                                    'class' => ' btn btn-tools-secondary',
                                    'title' => 'Rispondi a richiesta immagine'
                                ]);
                            }
                            return '';
                        }
                    ]
                ],
            ],
        ],
    ]); ?>
</div>
