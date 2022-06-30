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

<div class="attachments-list m-t-20 row">

    <?php if ($filesList) : ?>

        <label class="text-uppercase col-md-12"><?= FileModule::t('amosattachments', '#attach'); ?></label>

    <?php else: ?>

        <label class="text-uppercase col-md-12"><?= FileModule::t('amosattachments', '#attach'); ?></label>
        <div class="no-items text-muted col-md-12"><?= FileModule::t('amosattachments', '#no_attach'); ?></div>


    <?php endif; ?>


    <?php foreach ($filesList as $file) : ?>

        <div id="attachment-list-item-<?=$file['file_id']?>" class="attachment-list-item col-md-6">
            <div class="attachment-list-item-name">
                <div>
                    <?php if ((in_array(strtolower($file['type']), ['jpg', 'png', 'jpeg', 'svg']))) : ?>
                        <span class="icon icon-image icon-sm mdi mdi-image"></span>
                    <?php elseif ((in_array(strtolower($file['type']), ['pdf']))) : ?>
                        <span class="icon icon-pdf icon-sm mdi mdi-file-pdf-box"></span>
                    <?php elseif ((in_array(strtolower($file['type']), ['doc', 'docx']))) : ?>
                        <span class="icon icon-word icon-sm mdi mdi-file-word-box"></span>
                    <?php elseif ((in_array(strtolower($file['type']), ['xls', 'xlsx']))) : ?>
                        <span class="icon icon-excel icon-sm mdi mdi-file-excel-box"></span>
                    <?php elseif ((in_array(strtolower($file['type']), ['zip', 'rar']))) : ?>
                        <span class="icon icon-link icon-sm mdi mdi-folder-zip"></span>
                    <?php else : ?>
                        <span class="icon icon-link icon-sm mdi mdi-link-box"></span>
                    <?php endif ?>
                </div>
                <?= $file['filename']; ?>
            </div>
            <div class="attachment-list-item-action">
                <?= $file['preview']; ?>
                <!-- < ?= $file['downloadButton']; ?> -->
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
