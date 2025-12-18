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
        $(".attachments-list-view-cat-{$idRand}").hide();

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
    <div class="col-md-12">
        <div class="d-flex">
            <?php if ($filesList): ?>
                <label class="text-uppercase"><?= $label ?></label>
                <?php if ($categorizationEnabled): ?>
                    <div class="ml-auto">
                        <!--<button type="button" class="attachments-list-switch btn btn-secondary btn-xs">Visualizza allegati divisi per categoria</button>-->
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="1" class="attachments-list-switch-<?= $idRand ?>"> <?= $txtSwitchView ?>
                            </label>
                        </div>
                    </div>
                <?php endif; ?>
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
        foreach ($array1 as $file) :
            echo $this->render('_item_attachments-list', [
                'file' => $file,
                'i' => $i,
                'label' => $label,
                'enableUpdateAltDescription' => $enableUpdateAltDescription,
                'setAltDescriprionAsName' => $setAltDescriprionAsName
            ]);
            $i++;
        endforeach; ?>
    </div>

        <?php if ((!$useOnlyOneColumn) && (!empty($array2))): ?>
        <div class="d-flex flex-column flex-nowrap">
            <?php
            $i = 1;
            foreach ($array2 as $file) :
                echo $this->render('_item_attachments-list', [
                    'file' => $file,
                    'i' => $i,
                    'label' => $label,
                    'enableUpdateAltDescription' => $enableUpdateAltDescription,
                    'setAltDescriprionAsName' => $setAltDescriprionAsName
                ]);
                $i++;
            endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

        <?php if ($categorizationEnabled) { ?>
            <div class="attachments-list-view-cat-<?= $idRand ?> w-100">
                <div class="d-flex flex-column flex-nowrap">
                    <?php
                    $j = 1;
                    foreach ($listByCat as $catName => $files){
                        ?>
                        <div class="col-md-12 m-t-20">
                            <h6><?= $catName ?></h6>
                            <?php foreach ($files as $file){
                                if ($setAltDescriprionAsName && !is_null($file['alt_description']) && $file['alt_description'] != '') {
                                    $file['filename'] = $file['alt_description'] . '.' . $file['type'];
                                }
                                ?>
                                <?=
                                $this->render('_item_attachments-list', [
                                    'file' => $file,
                                    'i' => $j,
                                    'label' => $label,
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
    <?php else: ?>
    <!--<label class="text-uppercase"><?= $label ?></label>-->
        <div class="no-items text-muted"><?=
            FileModule::t('amosattachments', '#no_attach')
            . (($categorizationEnabled) ? ' ' . FileModule::t('amosattachments', '#save_to_categorize') : '');
            ?></div>
    <?php endif; ?>
</div>
