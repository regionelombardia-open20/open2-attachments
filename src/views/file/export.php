<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\events\views\file
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

use kartik\grid\GridView;

use yii\web\View;

/**
 * @var yii\web\View $this
 * @var \open20\amos\attachments\models\search\FileSearch $model
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var string $currentView
 */
?>
<?= $this->render('_search', ['model' => $model]); ?>
<div class="event-index">
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'name',
        'model',
        'attribute',
        'creator' => [
            'label' => 'Creatore',
            'value' => function ($model) {
                if ($model->owner->id) {
                    $userProfile = UserProfile::findOne($model->owner->created_by);
                    return $userProfile->getNomeCognome();
                } else {
                    return '?';
                }
            }
        ],
        'hash',
        'type',
    ]
]);
?>
</div>
