<?php

use open20\amos\attachments\FileModule;
use open20\amos\attachments\assets\ModuleAttachmentsAsset;

ModuleAttachmentsAsset::register($this);
//$dataProvider->pagination->pageSize = 4;
?>

<div class="col-xs-12 gallery-masonry">
    <?php \yii\widgets\Pjax::begin([
        'enablePushState' => false, // to disable push state
        'enableReplaceState' => false // to disable replace state
    ]); ?>
    <?= \open20\amos\core\views\ListView::widget([

        'dataProvider' => $dataProvider,
        'viewParams' => ['attribute' => $attribute],
        'itemView' => '_item_gallery',
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


    ]) ?>
    <?php \yii\widgets\Pjax::end() ?>
</div>