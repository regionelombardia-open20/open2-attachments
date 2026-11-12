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
use open20\amos\core\module\BaseAmosModule;
//use kartik\datecontrol\DateControl;
use yii\widgets\DetailView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var open20\amos\attachments\models\AttachFileCategorie $model */
$this->title = strip_tags($model->titolo);
//$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/attachments']];
$this->params['breadcrumbs'][] = ['label' => FileModule::t('amosattachments',
    'Categorie Allegati'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attach-file-categorie-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
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
        ],
    ]) ?>
</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(BaseAmosModule::t('amosattachments', 'Chiudi'),
        Url::previous(), ['class' => 'btn btn-secondary']); ?>
</div>
