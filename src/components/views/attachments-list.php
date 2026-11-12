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
use open20\amos\core\helpers\Html;

//use himiklab\colorbox\Colorbox;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var array $filesList */
/** @var boolean $viewFilesCounter */
/** @var int $filesQuantity */
/** @var boolean $useOnlyOneColumn */
/** @var boolean $categorizationEnabled */
/** @var string $idRand */
/** @var string $label */

ModuleAttachmentsAsset::register($this);

if (!empty($filesList)) {
    if ($viewFilesCounter) {
        $js = <<<JS
    var filesQuantity = "$filesQuantity";

    var section_title = $("#section-attachments").find("h2");

    section_title.append(" (" + filesQuantity + ")");
    if(filesQuantity == 0){
        section_title.addClass("section-disabled");
    }

JS;
        $this->registerJs($js);
    }

    $confirm = FileModule::t('amosattachments', 'Are you sure you want to delete this item?');
    $deleteUrl = '/' . FileModule::getModuleName() . '/file/delete';

    $js2 = <<<JS
    $('.attachments-list-delete').on('click', function(e) {
        e.preventDefault();
        var id = encodeURI($(this).data('id'));
        var item_id = encodeURI($(this).data('item_id'));
        var model = encodeURI($(this).data('model'));
        var attribute = encodeURI($(this).data('attribute'));
        if (typeof krajeeDialog != "undefined") {
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
        } else {
            if(confirm("{$confirm}")){
                $.ajax({
                    url: '{$deleteUrl}?id='+id+'&item_id='+item_id+'&model='+model+'&attribute='+attribute,
                    type: 'post',
                    success: function () {
                        $('#attachment-list-item-'+id).remove();
                    }
                });
            };
        }
    });

JS;
    $this->registerJs($js2);

    if ($categorizationEnabled) {
        $js3 = <<<JS
    $(document).ready(function() {
        $('.attachments-list-switch-{$idRand}').on('change', function(e) {
            $(".attachments-list-view-standard-{$idRand}").toggle();
            $(".attachments-list-view-cat-{$idRand}").toggle();
        });
    });

JS;
        $this->registerJs($js3);
    }

    $listByCat = ArrayHelper::index($filesList, null, 'categoryNameFull');
    ksort($listByCat);

    $txtSwitchView = FileModule::t('amosattachments', '#file_list_switch_view')
        . Html::tag('span', '', ['class' => 'am am-info-outline m-l-5',
            'data-toggle' => 'tooltip',
            'title' => FileModule::t('amosattachments', '#file_list_switch_view_tooltip')]);
}
?>
<div class="attachments-list m-t-20 row">
    <?php if ($filesList) { ?>
        <div class="col-md-12">
            <div class="d-flex">
                <label class="text-uppercase"><?= $label ?></label>
                <?php if ($categorizationEnabled) { ?>
                    <div class="ml-auto">
                        <!--<button type="button" class="attachments-list-switch btn btn-secondary btn-xs">Visualizza allegati divisi per categoria</button>-->
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="1"
                                       class="attachments-list-switch-<?= $idRand ?>"> <?= $txtSwitchView ?>
                            </label>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="attachments-list-view-standard-<?= $idRand ?> w-100">
            <?php
            $array1 = [];
            $array2 = [];
            $len = ceil(count($filesList) / 2);
            if ($len) {
                if ($useOnlyOneColumn) {
                    $array1 = $filesList;
                } else {
                    list($array1, $array2) = array_chunk($filesList, ceil(count($filesList) / 2));
                }
            }
            ?>

            <div class="d-flex flex-column flex-nowrap">
                <?php
                $i = 1;
                foreach ($array1 as $file) {
                    echo $this->render('_item_attachments-list', [
                        'file' => $file,
                        'i' => $i,
                        'label' => $label,
                        'enableUpdateAltDescription' => $enableUpdateAltDescription,
                        'setAltDescriprionAsName' => $setAltDescriprionAsName
                    ]);
                    $i++;
                } ?>
            </div>

            <?php if ((!$useOnlyOneColumn) && (!empty($array2))) { ?>
                <div class="d-flex flex-column flex-nowrap">
                    <?php
                    $i = 1;
                    foreach ($array2 as $file) {
                        echo $this->render('_item_attachments-list', [
                            'file' => $file,
                            'i' => $i,
                            'label' => $label,
                            'enableUpdateAltDescription' => $enableUpdateAltDescription,
                            'setAltDescriprionAsName' => $setAltDescriprionAsName
                        ]);
                        $i++;
                    } ?>
                </div>
            <?php } ?>
        </div>

        <?php if ($categorizationEnabled) { ?>
            <div class="attachments-list-view-cat-<?= $idRand ?> w-100" - style="display: none">
                <div class="d-flex flex-column flex-nowrap">
                    <?php
                    $j = 1;
                    foreach ($listByCat as $catName => $files) {
                        ?>
                        <div class="col-md-12 m-t-20">
                            <h6><?= $catName ?></h6>
                            <?php foreach ($files as $file) {
                                if ($setAltDescriprionAsName && !is_null($file['alt_description']) && $file['alt_description'] != '') {
                                    $file['filename'] = $file['alt_description'] . '.' . $file['type'];
                                }
                                ?>
                                <?=
                                $this->render('_item_attachments-list', [
                                    'file' => $file,
                                    'i' => $j,
                                    'label' => $label,
                                    'showCategory' => true
                                ]);
                                ?>
                                <?php
                                $j++;
                            }
                            ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
            <div class="col-md-12 m-t-20">
                <label class="text-uppercase"><?= $label ?></label>
                <div class="no-items text-muted"><?= FileModule::t('amosattachments', '#no_attach')
                    . (($categorizationEnabled && Yii::$app->controller->action->id != 'view') ? ' ' . FileModule::t('amosattachments', '#save_to_categorize') : ''); ?>
                </div>
            </div>
    <?php } ?>
</div>