<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\attachments\components\views
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
use open20\amos\attachments\assets\ModuleAttachmentsAsset;
use open20\amos\core\views\ListView;
use open20\amos\attachments\models\Shutterstock;

use yii\widgets\Pjax;

ModuleAttachmentsAsset::register($this);
$module = \Yii::$app->getModule('attachments');
$enableSandbox = true;
if (isset($module->shutterstockConfigs['enableSandbox'])) {
    $enableSandbox = $module->shutterstockConfigs['enableSandbox'];
}

$urlPlaceholder = '/img/img_default.jpg';
if (file_exists(\Yii::getAlias('@frontend') . '/web/img/placeholder-img.gif')) {
    $urlPlaceholder = '/img/placeholder-img.gif';
}
?>
<?php if ($enableSandbox) { ?>
    <div class="alert alert-info" role="alert">
        <p>
            <strong><?= FileModule::t('amosattachments', "Sandbox abilitata") . ': ' ?></strong><?= FileModule::t('amosattachments', "potrai scaricare un numero illimitato di immagini con la filigrana di Shutterstock.") ?>
        </p>
    </div>
<?php } else { ?>
    <div class="alert alert-info" role="alert">
        <p>
            <?= FileModule::t('amosattachments', "Numero di immagini scaricabili rimanenti") . ': ' ?>
            <strong><?= Shutterstock::getDownloadRemainingFromDb() ?></strong>
        </p>
    </div>
<?php } ?>

<?php if (!empty($errorMessage)) { ?>
    <div class="alert alert-warning" role="alert">
        <?= $errorMessage ?>
    </div>
<?php } ?>
<div class="gallery-masonry m-t-20">

    <?php if (!empty($firstLoad)) { ?>
        <p><?= FileModule::t('amosattachments', "Inserisci i criteri sopra richiesti e clicca su cerca") ?></p>
    <?php } else { ?>
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'viewParams' => ['attribute' => $attribute, 'urlPlaceholder' => $urlPlaceholder],
            'itemView' => '_item_shutterstock',
            'masonry' => false,
            // Se masonry settato a TRUE decommentare e settare i parametri seguenti
            // nel CSS settare i seguenti parametri necessari al funzionamento tipo
            // .grid-sizer, .grid-item {width: 50%;}
            // Per i dettagli recarsi sul sito http://masonry.desandro.com

            // 'masonrySelector' => '.grid',
            // 'masonryOptions' => [
            //     'itemSelector' => '.grid-item',
            //     'columnWidth' => '.grid-sizer',
            //     'percentPosition' => 'true',
            //     'gutter' => 3
            // ],
        ])
        ?>
    <?php } ?>


    <?php
    if (!empty($modelSearch->pagination)) {
        echo \open20\amos\core\views\AmosLinkPager::widget([
            'pagination' => $modelSearch->pagination,
            'showSummary' => true,
            'bottomPositionSummary' => true,
        ]);
    } ?>

</div>