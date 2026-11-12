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

use yii\widgets\Pjax;

ModuleAttachmentsAsset::register($this);
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