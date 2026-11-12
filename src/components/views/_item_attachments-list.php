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

/**
 * @var $file array
 * @var $i int
 * @var $showCategory bool
 */
use open20\amos\attachments\FileModule;
use yii\helpers\Html;

$css = <<< CSS
    .attachment-list-item-name {
        align-items: flex-start;
        flex-wrap: wrap;
    }

    .warning-cat {
        color: #a61919;
    }

CSS;
$this->registerCss($css);

$filenameToRender = (isset($showCategory)) ? $file['filename_cat'] : $file['filename'];
?>
    <div id="attachment-list-item-<?= $file['file_id'] ?>" class="attachment-list-item order-<?= $i ?>">
        <div class="attachment-list-item-name">
            <div>
                <?php if (in_array(strtolower($file['type']), ['jpg', 'png', 'jpeg', 'svg'])) : ?>
                    <span class="icon icon-image icon-sm mdi mdi-image"></span>
                <?php elseif (in_array(strtolower($file['type']), ['pdf'])) : ?>
                    <span class="icon icon-pdf icon-sm mdi mdi-file-pdf-box"></span>
                <?php elseif (in_array(strtolower($file['type']), ['doc', 'docx'])) : ?>
                    <span class="icon icon-word icon-sm mdi mdi-file-word-box"></span>
                <?php elseif (in_array(strtolower($file['type']), ['xls', 'xlsx'])) : ?>
                    <span class="icon icon-excel icon-sm mdi mdi-file-excel-box"></span>
                <?php elseif (in_array(strtolower($file['type']), ['zip', 'rar'])) : ?>
                    <span class="icon icon-link icon-sm mdi mdi-folder-zip"></span>
                <?php else : ?>
                    <span class="icon icon-link icon-sm mdi mdi-link-box"></span>
                <?php endif ?>
            </div>
            <?= $filenameToRender ?>
    
        </div>
        <div class="attachment-list-item-action">
            <?= $file['preview']; ?>
            <!-- < ?= $file['downloadButton']; ?> -->
            <?php if($enableUpdateAltDescription): ?>
                <?= Html::a(Html::tag('span', '', ['class' => 'am am-edit']),'#' , ['data-toggle'=>'tooltip','class' => '', 'title'=>FileModule::t('amosattachments', '#image_attributes') ,'onclick'=>'event.preventDefault(); $(this).parent().next().toggleClass("hide")']); ?>                
            <?php else: ?>
            
            <?= $file['updateButton']; ?>
 <?php endif; ?>
            <?= $file['sortButtons']; ?>
            <?= $file['deleteButton']; ?>
        </div>
        
        <?php if($enableUpdateAltDescription): ?>
            
            <div class="attachment-list-item-alt-desciption hide">

                <div class="form-group input-prepend">
                    <?= Html::label(FileModule::t('amosattachments', 'alt'), '', ['data-toggle'=>'tooltip','title'=>FileModule::t('amosattachments', '#alt_description_title'),'class' => 'btn btn-primary']) ?>
                    <?= Html::input('text', 'update_alt_desription['.$file['file_id'].']', $file['alt_description'], ['style'=>'display:block','class' => 'form-control','placeholder'=>FileModule::t('amosattachments', '#alt_description')]); ?>
                </div>

            </div>
        
        <?php endif; ?>
     
    </div>

<?= \himiklab\colorbox\Colorbox::widget([
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
