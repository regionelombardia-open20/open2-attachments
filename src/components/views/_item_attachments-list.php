<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\components\views
 * @category   CategoryName
 */

/**
 * @var $file array
 * @var $i int
 *
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
            <?= $file['filename']; ?>
    
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
