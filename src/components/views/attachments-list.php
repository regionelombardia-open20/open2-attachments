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

?>

<div class="attachments-list col-xs-12 nop">

    <?php if ($filesList) : ?>

        <label><?= FileModule::t('amosattachments', '#attach_list_title'); ?></label>

    <?php else: ?>

        <div class="no-items"><?= FileModule::t('amosattachments', '#attach_list_no_items'); ?></div>

    <?php endif; ?>


    <?php foreach ($filesList as $file) : ?>

        <div class="attachment-list-item col-xs-12 nop">
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
