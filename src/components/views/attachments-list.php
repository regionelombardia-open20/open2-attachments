<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\components\views
 * @category   CategoryName
 */

use open20\amos\attachments\assets\ModuleAttachmentsAsset;
use open20\amos\attachments\FileModule;
use himiklab\colorbox\Colorbox;

/**
 * @var array $filesList
 * @var bool $viewFilesCounter
 * @var int $filesQuantity
 */

ModuleAttachmentsAsset::register($this);

if ($viewFilesCounter) {
    $this->registerJs(<<<JS

    var filesQuantity = "$filesQuantity";

    var section_title = $("#section-attachments").find("h2");

    section_title.append(" (" + filesQuantity + ")");
    if(filesQuantity == 0){
        section_title.addClass("section-disabled");
    }

JS
    );
}

$confirm = FileModule::t('amosattachments', 'Are you sure you want to delete this item?');
$deleteUrl = '/' . FileModule::getModuleName() . '/file/delete';

$this->registerJs(<<<JS
    $('.attachments-list-delete').on('click', function(e) {
        e.preventDefault();
        var id = encodeURI($(this).data('id'));
        var item_id = encodeURI($(this).data('item_id'));
        var model = encodeURI($(this).data('model'));
        var attribute = encodeURI($(this).data('attribute'));
        if (typeof krajeeDialog != "undefined") {
            krajeeDialog.confirm("{$confirm}", function (result) {
                if (result) { // ok button was pressed
                    $.ajax({
                        url: '{$deleteUrl}?id='+id+'&item_id='+item_id+'&model='+model+'&attribute='+attribute,
                        type: 'post',
                        success: function () {
                            $('#attachment-list-item-'+id).remove();
                        }
                    });
                }
            });
        }else{
            if(confirm("{$confirm}")){
                    $.ajax({
                        url: '{$deleteUrl}?id='+id+'&item_id='+item_id+'&model='+model+'&attribute='+attribute,
                        type: 'post',
                        success: function () {
                            $('#attachment-list-item-'+id).remove();
                        }
                    });
            };
        }

    });
JS
);

?>

<div class="attachments-list m-t-20 row">

    <?php if ($filesList) : ?>

        <label class="text-uppercase col-md-12"><?= $label ?></label>

    <?php else: ?>

        <label class="text-uppercase col-md-12"><?= $label ?></label>
        <div class="no-items text-muted col-md-12"><?= FileModule::t('amosattachments', '#no_attach'); ?></div>


    <?php endif; ?>

    <?php
    $array1 = [];
    $array2 = [];
    $len = ceil(count($filesList) / 2);
    if($len){
        list($array1, $array2) = array_chunk($filesList, ceil(count($filesList) / 2));
    }
    ?>

<div class="d-flex flex-column flex-nowrap">
    <?php
    $i = 1;
    foreach ($array1 as $file) :
        echo $this->render('_item_attachments-list', [
            'file' => $file,
            'i' => $i,
            'label' => $label,
        ]);
        $i++;
    endforeach; ?>
</div>

<div class="d-flex flex-column flex-nowrap">
    <?php
    $i = 1;
    foreach ($array2 as $file) :
        echo $this->render('_item_attachments-list', [
            'file' => $file,
            'i' => $i,
            'label' => $label,
        ]);
        $i++;
    endforeach; ?>
</div>
</div>
