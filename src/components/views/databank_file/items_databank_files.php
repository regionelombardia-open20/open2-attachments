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

use yii\widgets\Pjax;

ModuleAttachmentsAsset::register($this);
\open20\amos\attachments\utility\AttachmentsUtility::filterFilesDoesntExit($dataProvider);
?>

<div class="gallery-masonry-attachments m-t-20 m-b-20">
<!--    --><?php //Pjax::begin([
//        'enablePushState' => false,
//        'enableReplaceState' => false
//    ]);
    ?>
    
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'viewParams' => ['attribute' => $attribute, 'file_ids' => $file_ids],
        'itemView' => '_item_databank_file',
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
    
<!--    --><?php //Pjax::end() ?>
</div>