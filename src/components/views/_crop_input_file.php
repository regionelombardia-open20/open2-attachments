<?php
/**
 * @var $crop \open20\amos\attachments\components\CropInput
 * @var $aspectRatio
 * @var $customHint
 * @var $enableUploadFromGallery
 */

use open20\amos\attachments\FileModule;
use open20\amos\attachments\components\GalleryInput;

$js = <<<JS
     
JS;
$this->registerJs($js);

$enableDropdown = $enableUploadFromGallery || $enableUploadFromShutterstock;
$classEnableDropdown = 'display:none';

?>

<?php // SELECTION FROM GALLERY AND DROPDOWN ?>
    <div id="crop-dropdown-container-id-<?= $crop->attribute ?>">
        <div class="input-group">
            <div class="file-caption form-control" tabindex="1">

                <input class="file-caption-name" onkeydown="return false;" onpaste="return false;" tabindex="-1"
                       placeholder="<?= FileModule::t('amosattachments', "Seleziona file ...") ?>">
            </div>
            <div class="input-group-btn">
                <?php if($enableDropdown){?>
                <?= $this->render('_dropdown_crop', [
                    'attribute' => $crop->attribute,
                    'enableUploadFromGallery' => $enableUploadFromGallery,
                    'enableUploadFormDatabankFile' => false,
                    'enableUploadFromShutterstock' => $enableUploadFromShutterstock
                ]) ?>
                <?php } else { ?>
                    <button class="dropdown-toggle btn btn-primary btn-file"
                            id="btn-dropdown-crop-browse-<?= $crop->attribute ?>" type="button">
                        <span class="mdi mdi-image"></span><?= FileModule::t('amosattachments', 'Inserisci immagine') ?>
                    </button>
                <?php } ?>
            </div>
        </div>

    </div>


<div id="crop-input-container-id-<?= $crop->attribute ?>" style="<?= $classEnableDropdown ?>"
     class="crop-input-container">
    <?php if ($aspectRatio == '1.7' && $customHint) { ?>
        <?php
        $ratioTooltipText = FileModule::t('amosattachments', '#default_message');
        $ratioTooltip = " <button type='button' data-toggle='tooltip' style='border:0; background:transparent' data-placement='top' title=' $ratioTooltipText'>
                            <span class='am am-info'></span>
                        </button>"; ?>

        <?= $crop->form->field($crop->model, $crop->attribute, ['enableError' => false])
            ->fileInput($crop->options)
            ->label(
                FileModule::t('amosattachments', '#attach_label'),
                ['title' => FileModule::t('amosattachments', '#attach_label_title')]
            )
            ->hint($customHint . $ratioTooltip);
        ?>

    <?php } else { ?>
        <?= $crop->form->field($crop->model, $crop->attribute, ['enableError' => false])
            ->fileInput($crop->options)
            ->label(
                FileModule::t('amosattachments', '#attach_label'),
                ['title' => FileModule::t('amosattachments', '#attach_label_title')]
            );
        ?>
    <?php } ?>
    <?= \yii\helpers\Html::hiddenInput('uploadedFromSource', null, ['id' => 'uploaded-from-source']) ?>
</div>


