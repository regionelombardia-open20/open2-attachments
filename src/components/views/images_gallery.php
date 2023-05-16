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
$urlPlaceholder = '/img/img_default.jpg';
if(file_exists(\Yii::getAlias('@frontend').'/web/img/placeholder-img.gif')){
    $urlPlaceholder = '/img/placeholder-img.gif';
}
?>

<div class="gallery-masonry m-t-20">

    
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'viewParams' => ['attribute' => $attribute,  'urlPlaceholder' => $urlPlaceholder],
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
    ])
    ?>
    
</div>