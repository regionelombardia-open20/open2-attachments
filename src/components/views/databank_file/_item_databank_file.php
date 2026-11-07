<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\components\views
 * @category   CategoryName
 *
 * @var $attribute
 */

use open20\amos\core\icons\AmosIcons;
use open20\amos\attachments\FileModule;
use yii\helpers\Html;


$selectedIds = [];
$selectedClass = '';
$iconSelected = 'checkbox-blank-outline';
if(!empty($file_ids)){
    $selectedIds = explode(',', $file_ids);
        
}
if(in_array($model->id, $selectedIds)){
    $selectedClass= 'file-item-selected';
    $iconSelected = 'checkbox-marked';
}
?>

<div class="attachment-databank-item content-item <?= $selectedClass ?>" id="content-item-id-<?=$attribute?>-<?= $model->id?>">


    <div class="info-attachment">
        <span class="checkbox-selection mdi mdi-<?= $iconSelected ?> icon-file-check mdi-24px"></span>
        <?= $model->getAttachmentIcon(); ?>
           
            <?php $label  = $this->render('_icon', ['documentMainFile' => $model]); ?>
            <div class="text-truncate">
            <?php echo Html::a(
                $model->name,
                '#',
                [
                    'id' => 'file-' . $model->id,
//                    'class' => 'show-detail-file',
                    'class' => 'select-link select-file-'.$attribute,
                    'title' => FileModule::t('amosattachments', 'seleziona file' .' '.  $model->name),
                    'data' => [
                        'key' => $model->id,
                        'attribute' => $attribute,
                        'name' => $model->name.'.' . $model->type
                    ]
                ]
            ) ?>
            </div>

    </div>
</div>
