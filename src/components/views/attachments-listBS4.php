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

use open20\amos\attachments\assets\ModuleAttachmentsAsset;
use open20\amos\attachments\FileModule;
use himiklab\colorbox\Colorbox;
use open20\design\assets\BootstrapItaliaDesignAsset;
$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);

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
<?php if(!$disableLabelList && !empty($filesList)){ ?>
    <label><?= FileModule::t('amosattachments', '#attach_list_title'); ?></label>
<?php } ?>


<?php if(empty($filesList)){ ?>
    <div class="no-items"><?= FileModule::t('amosattachments', '#attach_list_no_items'); ?></div>
<?php } ?>


<?php foreach ($filesList as $file) : ?>
        <div class="attachment-item card-wrapper my-2 card-teaser card-one-block col-lg-4 col-md-6">
            <div class="card card-teaser card-one-block  shadow p-4">
                <svg class="icon icon-primary">
                    <use xlink:href=" <?=$bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-clip"></use>
                </svg>
                <div class="card-body">
                    <?= $file['filename']; ?> 
                    <?= $file['preview']; ?>
                    <?= $file['downloadButton']; ?>
                    <?= $file['sortButtons']; ?>
                    <?= $file['deleteButton']; ?>             
                </div>
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

