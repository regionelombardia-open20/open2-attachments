<?php
/**
 * @var $attribute string
 * @var $enableUploadFormDatabankFile string
 * @var $enableUploadFromGallery string
 * @var $enableUploadFromShutterstock string
 */

use open20\amos\attachments\FileModule;

?>
<button class="dropdown-toggle btn btn-primary btn-file" id="crop-browse-<?= $attribute?>" type="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
    <span class="mdi mdi-image"></span><?= FileModule::t('amosattachments', 'Inserisci immagine') ?>
    <span class="caret"></span>
</button>

<ul class="dropdown-menu pull-right" aria-labelledby="dLabel">
    <li><a href="#" id="btn-dropdown-crop-browse-<?= $attribute?>"><?= FileModule::t('amosattachments', 'Carica da dispositivo') ?></a></li>
    <?php if ($enableUploadFormDatabankFile) { ?>
        <li><a data-attribute="<?= $attribute?>" class="open-modal-databank-file"
               href="#"><?= FileModule::t('amosattachments', 'Seleziona da allegati') ?></a></li>
    <?php } ?>
    <?php if ($enableUploadFromGallery) { ?>
        <li><a data-attribute="<?= $attribute?>" data-gallery="1" class="open-modal-gallery"
               href="#"><?= FileModule::t('amosattachments', 'Seleziona da databank immagini') ?></a></li>
    <?php } ?>
    <?php if ($enableUploadFromShutterstock) { ?>
        <li><a data-attribute="<?= $attribute?>" data-gallery="1" class="open-modal-shutterstock"
               href="#"><?= FileModule::t('amosattachments', 'Seleziona da shutterstock') ?></a></li>
    <?php } ?>
</ul>
