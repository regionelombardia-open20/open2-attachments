<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\components\views
 * @category   CategoryName
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
            <strong><?= FileModule::t('amosattachments', "Sandbox abilitata") . ': ' ?></strong><?= FileModule::t('amosattachments', "potrai scaricare un numero illimitato di immagini, queste avranno la filigrana con il marchio di Shutterstock.") ?>
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