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
