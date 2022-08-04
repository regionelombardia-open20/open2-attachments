<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\components\views
 * @category   CategoryName
 */

use open20\amos\attachments\components\GalleryInput;
use open20\amos\attachments\FileModule;
use open20\amos\attachments\assets\ModuleAttachmentsAsset;
use open20\amos\core\icons\AmosIcons;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;

use uitrick\yii2\widget\upload\crop\UploadCropAsset;

use yii\bootstrap\Modal;

use yii\helpers\Html;
use yii\helpers\Json;

$moduleName = FileModule::getModuleName();

$inputId = isset($crop->options['id']) && (!empty($crop->options['id']))
    ? $crop->options['id']
    : Html::getInputId($crop->model, $crop->attribute)
;
$modalId = 'cropper-modal-' . $crop->imageOptions['id'];
$csrfParam = \Yii::$app->request->csrfParam;

$jsCrop = <<<JS
    $('#$inputId').change(function(){
         var csrf = $('form input[name="$csrfParam"]').val();

         $.ajax({
          method: 'get',
          url: "/attachments/attach-gallery-image/delete-from-session-ajax",
          data: {csrf: csrf},
          success: function(data){
              if(data.success == true){
                }
          }
        });
    });

//pulsante cancella nel modale
$('.crop-cancel-button').on('click',function(){
    $('#$inputId').val(null);
    $('#$modalId').modal('hide');
});

JS;

$this->registerJs($jsCrop);

if (!empty(\Yii::$app->params['bsVersion']) && \Yii::$app->params['bsVersion'] == '4.x') {
    BootstrapItaliaCustomSpriteAsset::register($this);
} else {
    ModuleAttachmentsAsset::register($this);
}
$cropperInputId = isset($crop->options['id']) && (!empty($crop->options['id']))
    ? ('cropInput_' . $crop->options['id'])
    : ('cropInput_' . $crop->attribute)
;

$closeButtonModalId = $crop->imageOptions['id'] . '_button_cancel';

if ($crop->isFrontend == false) {
    ModuleAttachmentsAsset::register($this);
}

$attachament = $crop->model->{$crop->attribute};

$alertString = FileModule::t('amosattachment', "Estensione file non permessa, inserire un file con un'estensione consentita.");
?>

<div class="uploadcrop attachment-uploadcrop" id="<?= $cropperInputId ?>">
    <?php if ($aspectRatio == '1.7' && $customHint): ?>
        <?php
        $ratioTooltipText = FileModule::t('amosattachments', '#default_message');
        $ratioTooltip = " <button type='button' data-toggle='tooltip' style='border:0; background:transparent' data-placement='top' title=' $ratioTooltipText'>
                            <span class='am am-info'></span>
                        </button>"; ?>

        <?= $crop->form->field($crop->model, $crop->attribute, ['enableError' => false])
            ->fileInput($crop->options)
            ->label(FileModule::t('amosattachments', '#attach_label'),
                ['title' => FileModule::t('amosattachments', '#attach_label_title')])
            ->hint($customHint . $ratioTooltip);
        ?>

    <?php else: ?>

        <?= $crop->form->field($crop->model, $crop->attribute, ['enableError' => false])
            ->fileInput($crop->options)
            ->label(FileModule::t('amosattachments', '#attach_label'),
                ['title' => FileModule::t('amosattachments', '#attach_label_title')]);
        ?>

    <?php endif; ?>

    <?= Html::hiddenInput($crop->attribute . '_data', '', ['class' => 'cropper-data']); ?>

    <div class="preview-pane <?= (!is_null($crop->defaultPreviewImage)) ? 'image-find' : '' ?>">
        <?php
        $closeButtonClass = is_null($crop->defaultPreviewImage) ? ' hidden' : '';
        if (!$crop->hidePreviewDeleteButton) {
            echo Html::a(AmosIcons::show('close', ['class' => '']), 'javascript:void(0)',
                [
                    'class' => 'deleteImageCrop btn btn-danger btn-sm btn-icon' . $closeButtonClass,
                    'title' => FileModule::t('amosattachments', '#attach_delete_image_crop'),
                    'data' => [
                        'id' => $attachament ? $attachament->id : null,
                        'item_id' => $crop->model->id,
                        'model' => get_class($crop->model),
                        'attribute' => $crop->attribute
                    ]
                ]);
        }
        ?>
        <div id="preview-container-<?= $crop->attribute ?>" class="preview-container">
            <?php
            if (!is_null($crop->defaultPreviewImage)) {
                $defaultPreviewImageOptions = [
                    'id' => Yii::$app->getSecurity()->generateRandomString(10),
                    'class' => 'preview_image'
                ];
                echo Html::img($crop->defaultPreviewImage, $defaultPreviewImageOptions);
            }
            ?>
        </div>
    </div>

    <?php if (!empty(\Yii::$app->params['bsVersion']) && \Yii::$app->params['bsVersion'] == '4.x') : ?>

        <?php
        yii\bootstrap4\Modal::begin([
            'id' => 'cropper-modal-' . $crop->imageOptions['id'],
            'title' => '<h2>' . FileModule::t('amosattachments', '#crop_title') . '</h2>',
            'closeButton' => $cropModalCloseButton,
            'footer' => '<div class="cropper-btns">'
                . Html::button(
                    FileModule::t('amosattachments', '#cancel_btn'),
                    [
                        'id' => $crop->imageOptions['id'] . '_button_cancel',
                        'class' => 'crop-cancel-button btn btn-link mr-3'
                    ]
                )
                . Html::button(
                    FileModule::t('amosattachments', '#accept_btn'),
                    [
                        'id' => $crop->imageOptions['id'] . '_button_accept',
                        'class' => 'btn btn-primary cropper-done'
                    ]
                )
                . '</div>',
            'size' => yii\bootstrap4\Modal::SIZE_LARGE,
            'clientOptions' => ['backdrop' => 'static'] //To prevent closing when you drag outside the modal window
        ]); ?>
        <div id="image-source<?= $crop->imageOptions['id'] ?>" class="row cropper-body nom ">
            <!-- Image crop area -->
            <div class="col-md-9">
                <div class="cropper-wrapper"></div>
            </div>
            <!-- preview column -->
            <div class="col-md-3">
                <div class="cropper-preview preview-lg"></div>
            </div>
            <div class="col-md-3 mt-3">
                <div class="btn-group tools">
                    <button type="button" class="btn btn-primary rotate_<?= $crop->attribute ?>" data-type="rotate"
                            data-option="-90" title="Rotate Left">
                        <span class="docs-tooltip" data-animation="false">
                            <?= AmosIcons::show('rotate-left', ['class' => 'am']); ?>
                        </span>
                    </button>
                    <button type="button" class="btn btn-primary rotate_<?= $crop->attribute ?>" data-type="rotate"
                            data-option="90" title="Rotate Right">
                        <span class="docs-tooltip" data-animation="false">
                            <?= AmosIcons::show('rotate-right', ['class' => 'am']); ?>
                        </span>
                    </button>
                </div>

                <?php
                if (isset($aspectRatioChoices) && !empty($aspectRatioChoices)):
                    ?>
                    <div id="crop-buttons-id" class="btn-group tools">
                        <?php
                        foreach ($aspectRatioChoices as $choice):
                            ?>
                            <button type="button" class="btn btn-xs btn-info aspectratio_<?= $crop->attribute ?>"
                                    data-type="aspectRatio"
                                    data-option="<?= isset($choice['value']) ? $choice['value'] : '' ?>"
                                    title="<?= isset($choice['title']) ? $choice['title'] : '' ?>">
                                <?= isset($choice['label']) ? $choice['label'] : '' ?>
                            </button>
                        <?php
                        endforeach;
                        ?>
                    </div>
                <?php
                endif;
                ?>
            </div>
            <?php if ($aspectRatio == 1.7) : ?>
                <div class="col-md-3 mt-3">
                    <p><?= FileModule::t('amosattachments', 'Si consiglia il caricamento di immagini orizzontali in proporzione 16:9 (dimensione consigliata minima: 1348x758px, ideale: 1920x1080px)') ?></p>
                </div>
            <?php endif; ?>


        </div>
        <?php yii\bootstrap4\Modal::end(); ?>

    <?php else : ?>
        <?php Modal::begin([
            'id' => 'cropper-modal-' . $crop->imageOptions['id'],
            'header' => '<h2>' . FileModule::t('amosattachments', '#crop_title') . '</h2>',
            'closeButton' => [],
            'footer' => '<div class="row cropper-btns">'
                . Html::button(FileModule::t('amosattachments', '#cancel_btn'),
                    ['id' => $crop->imageOptions['id'] . '_button_cancel', 'class' => 'btn btn-secondary', 'data-dismiss' => 'modal'])
                . Html::button(FileModule::t('amosattachments', '#accept_btn'),
                    ['id' => $crop->imageOptions['id'] . '_button_accept', 'class' => 'btn btn-navigation-primary cropper-done']) . '</div>',
            'size' => Modal::SIZE_LARGE,
            'clientOptions' => ['backdrop' => 'static'] //To prevent closing when you drag outside the modal window
        ]);
        ?>
        <div id="image-source<?= $crop->imageOptions['id'] ?>" class="row cropper-body nom">
            <!-- Image crop area -->
            <div class="col-md-9">
                <div class="cropper-wrapper"></div>
            </div>
            <!-- preview column -->
            <div class="col-md-3">
                <div class="cropper-preview preview-lg"></div>

                <div class="btn-group tools m-t-15 m-r-15 m-l-15 ">
                    <button type="button" class="btn btn-primary rotate_<?= $crop->attribute ?>" data-type="rotate"
                            data-option="-90" title="Rotate Left">
                        <span class="docs-tooltip" data-animation="false">
                            <?= AmosIcons::show('rotate-left', ['class' => 'am']); ?>
                        </span>
                    </button>
                    <button type="button" class="btn btn-primary rotate_<?= $crop->attribute ?>" data-type="rotate"
                            data-option="90" title="Rotate Right">
                        <span class="docs-tooltip" data-animation="false">
                            <?= AmosIcons::show('rotate-right', ['class' => 'am']); ?>
                        </span>
                    </button>
                </div>

                <?php if ($aspectRatio == 1.7) : ?>
                    <div class="image-size-description m-t-15 m-r-15 m-l-15 small">
                        <?= Yii::t('amosattachments', '#default_message') ?>
                        <!--<p>Si consiglia il caricamento di immagini orizzontali in proporzione <strong>16:9</strong>. La dimensione consigliata minima è <strong>1348 x 758px</strong>,
                        quella ideale è <strong>1920 x 1080px</strong>.</p>-->
                    </div>
                <?php endif; ?>
                <?php
                if (isset($aspectRatioChoices) && !empty($aspectRatioChoices)) { ?>
                    <div id="crop-buttons-id" class="btn-group tools">
                        <?php
                        foreach ($aspectRatioChoices as $choice) {
                            ?>
                            <button type="button" class="btn btn-xs btn-info aspectratio_<?= $crop->attribute ?>"
                                    data-type="aspectRatio"
                                    data-option="<?= isset($choice['value']) ? $choice['value'] : '' ?>"
                                    title="<?= isset($choice['title']) ? $choice['title'] : '' ?>">
                                <?= isset($choice['label']) ? $choice['label'] : '' ?>
                            </button>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                }
                ?>
            </div>


        </div>
        <?php Modal::end(); ?>

    <?php endif; ?>
</div>

<?php
// SELECTION FROM GALLERY
if ($crop->enableUploadFromGallery) {
    echo GalleryInput::widget(['attribute' => $crop->attribute]);
}

$css = <<<CSS
.cropper-alert + .cropper-body {
    display: none;
}
 .cropper-alert{
        font-size: 0px;
    }
    .cropper-alert::after{
        content: "$alertString";
        font-size:15px;
        margin-left: 60px;
}
CSS;

$this->registerCss($css);

$js = <<<JS
$(window).on('shown.bs.modal', function() { 
    $('#crop-buttons-id button[data-option = "1.7"]').trigger('click');
    $('.cropper-alert button').attr('data-dismiss', 'modal');
});

  //On delete button click
jQuery('.deleteImageCrop', '#{$cropperInputId}').on('click', function() {
    //Metadata
    var data = jQuery(this).data();
    
    //Hide the button
    jQuery(this).addClass('hidden');
    
    //Remove the image
    jQuery('.preview-container img', '#{$cropperInputId}').remove();
    
    //Clear crop if exists
    jQuery('.cropper-data', '#{$cropperInputId}').attr('val', '');
    
    jQuery.get('/{$moduleName}/file/delete',{
        'id': data.id,
        'item_id': data.item_id,
        'model': data.model,
        'attribute': data.attribute
    }, function(result) {
        //TODO
    }, 'json');
});

jQuery('.modal-body .tools>.rotate_{$crop->attribute}', '#{$cropperInputId}').on('click', function() {
    var data = jQuery(this).data();
    var image = $('.modal-body .cropper-wrapper>img', '#{$cropperInputId}');
    image.cropper(data.type, data.option);
    image.cropper('crop');
});

jQuery('.modal-body .tools>.aspectratio_{$crop->attribute}', '#{$cropperInputId}').on('click', function() {
    var data = jQuery(this).data();
    var image = $('.modal-body .cropper-wrapper>img', '#{$cropperInputId}');
    if (data.option == 'x') {
        image.cropper('clear');
    } else {
        image.cropper('crop');
        image.cropper('setAspectRatio', data.option);
    }
});

//On new image selected
jQuery('.modal-footer button[class*="cropper-done"]', '#{$cropperInputId}').on('click', function() {
    jQuery('.deleteImageCrop', '#{$cropperInputId}').removeClass('hidden');
});

jQuery('#{$closeButtonModalId}').click(function(e){
    e.preventDefault();
    $('#{$modalId}').modal('hide');
});
JS;

$this->registerJs($js, \yii\web\View::POS_READY);


UploadCropAsset::register($this);

$jcropOptions = ['inputField' => $inputField, 'jcropOptions' => $crop->jcropOptions];

$jcropOptions['maxSize'] = $crop->maxSize;

$jcropOptions = Json::encode($jcropOptions);

$jsCropper = <<<JS
    var options = {$jcropOptions};
    
    jQuery("#{$inputId}").uploadCrop(options);
JS;


$this->registerJs($jsCropper, \yii\web\View::POS_READY);
?>

