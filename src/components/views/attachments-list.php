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

    });
JS
);

?>

<div class="attachments-list col-xs-12 nop" style="width: auto; height: 250px; overflow: auto;">

    <?php if ($filesList) : ?>

        <label><?= FileModule::t('amosattachments', '#attach_list_title'); ?></label>

    <?php else: ?>

        <label class="text-uppercase"><?= FileModule::t('amosattachments', '#attach'); ?></label>
        <div class="no-items text-muted"><?= FileModule::t('amosattachments', '#no_attach'); ?></div>


    <?php endif; ?>


    <?php foreach ($filesList as $file) : ?>

        <div id="attachment-list-item-<?=$file['file_id']?>" class="attachment-list-item col-xs-12 nop">
            <div class="attachment-list-item-name">
                <?= $file['filename']; ?>
            </div>
            <div class="attachment-list-item-action">
                <?= $file['preview']; ?>
                <?= $file['downloadButton']; ?>
                <?= $file['sortButtons']; ?>
                <?= $file['deleteButton']; ?>
            </div>
        </div>

        <?= Colorbox::widget([
            'targets' => [
                '.att' . $file['id'] => [
                    'rel' => '.att' . $file['id'],
                    'photo' => true,
                    'scalePhotos' => true,
                    'width' => '100%',
                    'height' => '100%',
                    'maxWidth' => 800,
                    'maxHeight' => 600,
                ],
            ],
            'coreStyle' => 4,
        ]); ?>

    <?php endforeach; ?>

</div>
