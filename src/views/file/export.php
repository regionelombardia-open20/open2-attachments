<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\core\views\DataProviderView;
use open20\amos\events\AmosEvents;

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
    <?= \kartik\grid\GridView::widget(
        [
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
                            $userProfile = \open20\amos\admin\models\UserProfile::findOne($model->owner->created_by);

                            return $userProfile->getNomeCognome();
                        } else {
                            return '?';
                        }
                    }
                ],
                'hash',
                'type',
            ]
        ]
    ); ?>
</div>
