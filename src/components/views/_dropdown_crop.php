<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-attachments
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

/**
 * @var $attribute string
 * @var $enableUploadFormDatabankFile string
 * @var $enableUploadFromGallery string
 * @var $enableUploadFromShutterstock string
 * @var $uploadBtnLabel string
 */

use open20\amos\attachments\FileModule;

?>
<button style="font-size: initial" class="dropdown-toggle btn btn-primary btn-file" id="crop-browse-<?= $attribute?>" type="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
    <span class="mdi mdi-image"></span><?= $uploadBtnLabel ?: FileModule::t('amosattachments', 'Inserisci immagine') ?>
    <span class="caret"></span>
</button>

<ul class="dropdown-menu pull-right" aria-labelledby="dLabel">
    <li><a href="#" id="btn-dropdown-crop-browse-<?= $attribute?>"><?= FileModule::t('amosattachments', 'Carica da dispositivo') ?></a></li>
    <?php if ($enableUploadFormDatabankFile) { ?>
        <li><a data-attribute="<?= $attribute?>" class="open-modal-databank-file"
               href="#"><?= FileModule::t('amosattachments', 'Seleziona da allegati') ?></a></li>
    <?php } ?>
    <?php if ($enableUploadFromGallery) { ?>
        <li><a data-attribute="<?= $attribute?>" data-gallery="1" class="open-modal-gallery open-modal-gallery-<?= $attribute?>"
               href="#"><?= FileModule::t('amosattachments', 'Seleziona da databank immagini') ?></a></li>
    <?php } ?>
    <?php if ($enableUploadFromShutterstock) { ?>
        <li><a data-attribute="<?= $attribute?>" data-gallery="1" class="open-modal-shutterstock"
               href="#"><?= FileModule::t('amosattachments', 'Seleziona da shutterstock') ?></a></li>
    <?php } ?>
</ul>
