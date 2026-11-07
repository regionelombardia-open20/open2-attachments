<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\components\views
 * @category   CategoryName
 *
 * @var $inputField string
 */

use open20\amos\attachments\components\GalleryInput;
use open20\amos\attachments\FileModule;
use open20\amos\attachments\assets\ModuleAttachmentsAsset;
use open20\amos\core\icons\AmosIcons;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use open20\amos\core\utilities\ModalUtility;

use uitrick\yii2\widget\upload\crop\UploadCropAsset;

use yii\bootstrap\Modal;

use yii\helpers\Html;
use yii\helpers\Json;

$moduleName = FileModule::getModuleName();

$inputId = isset($crop->options['id']) && (!empty($crop->options['id']))
    ? $crop->options['id']
    : Html::getInputId($crop->model, $crop->attribute);

$inputFieldError = 'field-'.Html::getInputId($crop->model, $crop->attribute);
$modalId = 'cropper-modal-' . $crop->imageOptions['id'];
$enableUploadFromGallery = $crop->enableUploadFromGallery;
$enableUploadFromShutterstock = $crop->enableUploadFromShutterstock;
$enableMultipleUploads = $crop->enableMultipleUploads;
$maxMultipleUploads = $crop->maxMultipleUploads;
$enableAltDescription = $crop->enableAltDescription;
$enableUpdateAltDescription = $crop->enableUpdateAltDescription;
$csrfParam = \Yii::$app->request->csrfParam;

$filesUploaded = 'files_uploaded_'.str_replace('-','_',$inputId); 
$formId = $crop->form->options['id'];

$selection_label = FileModule::t('amosattachments', '#selected_images');
$max_upload_file_error = FileModule::t('amosattachments', '#max_upload_error',[$maxMultipleUploads]);


$jsCrop = <<<JS
    var $filesUploaded;
            
     $(document).ready(function(){
        $filesUploaded = [];

    });
        
    $('#$inputId').change(function(){
        var csrf = $('form input[name="$csrfParam"]').val();

        $('.{$crop->attribute}-input-update-alt-description').hide();
        
        $.ajax({
            method: 'get',
            url: "/attachments/attach-gallery-image/delete-from-session-ajax",
            data: {csrf: csrf},
            success: function(data){
                if(data.success == true){
                }
            }
        });
        
        $filesUploaded = [];
        
        let source = $('#uploaded-from-source').val();
        if(source == 'upload_from_pc')
            $('.{$crop->attribute}_alt_description').val('');
        else{
            let files_alt_description = $('#multiuploads-alt-descriptions-gallery-{$crop->attribute}').find('span').eq(0).text();
            $('.{$crop->attribute}_alt_description').val(files_alt_description);
            //console.log('ALT',files_alt_description);
        }
        
        $filesUploaded = this.files;
        //console.log('filesuplaoded',$filesUploaded);
        
    
       
    });
                    
//open dropdown
 $(document).on('click', '#btn-dropdown-crop-browse-{$crop->attribute}', function(e){
     e.preventDefault();
     $('#uploaded-from-source').val('upload_from_pc');
     $('#crop-input-container-id-{$crop->attribute} input[type="file"]').trigger('click');
 });
 
 //selected file name on fake input file
 $(document).on('change', '#crop-input-container-id-{$crop->attribute} input[type="file"]', function(e){
     e.preventDefault();
     let valInputFilePath = $(this).val();
     var filecaption = $('#crop-dropdown-container-id-$crop->attribute .file-caption-name');
     var fileName = valInputFilePath.replace(/^.*[\\\/]/, '');
           
     $(filecaption).val(fileName);
 });
 

JS;

$this->registerJs($jsCrop);

$classGallery = '';
if ($enableUploadFromGallery) {
    $classGallery = 'input-with-databank';
    echo GalleryInput::widget(['attribute' => $crop->attribute, 'multiSelection' => $enableMultipleUploads]);
}

if ($enableUploadFromShutterstock) {
    echo \open20\amos\attachments\components\ShutterstockInput::widget(['attribute' => $crop->attribute, 'aspectRatio' => $aspectRatio]);
}

if (!empty(\Yii::$app->params['bsVersion']) && \Yii::$app->params['bsVersion'] == '4.x') {
    BootstrapItaliaCustomSpriteAsset::register($this);
} else {
    ModuleAttachmentsAsset::register($this);
}
$cropperInputId = isset($crop->options['id']) && (!empty($crop->options['id']))
    ? ('cropInput_' . $crop->options['id'])
    : ('cropInput_' . $crop->attribute);

$closeButtonModalId = $crop->imageOptions['id'] . '_button_cancel';

if ($crop->isFrontend == false) {
    ModuleAttachmentsAsset::register($this);
}

$attachament = $crop->model->{$crop->attribute};
$alertString = FileModule::t('amosattachment', "Estensione file non permessa, inserire un file con un'estensione consentita.");
?>

    <div class="uploadcrop attachment-uploadcrop <?= $classGallery ?>" id="<?= $cropperInputId ?>">
        <?php // CROP INPUT FILE ?>
        <?= $this->render('_crop_input_file', [
            'crop' => $crop,
            'aspectRatio' => $aspectRatio,
            'customHint' => $customHint,
            'enableUploadFromGallery' => $enableUploadFromGallery,
            'enableUploadFromShutterstock' => $enableUploadFromShutterstock
        ]) ?>
        
        <?= Html::hiddenInput($crop->attribute . '_data', '', ['class' => 'cropper-data']); ?>
    
        <div class="<?= $crop->attribute ?>_data_info">
            <div class="crop"></div>
            <div class="source"></div>
            <div class="alt_description"></div>
        </div>
        
        <?php if($enableUpdateAltDescription): ?>
            <?php 
                $val = '';
                if($crop->model->{$crop->attribute} && !is_array($crop->model->{$crop->attribute}) && !is_null($crop->model->{$crop->attribute}->id)):
                    $idFile = $crop->model->{$crop->attribute}->id;
                    $val = $crop->model->{$crop->attribute}->alt_description;
            ?>
                    <div class="" style="height:45px;margin:15px 5px 0 5px">
                        <div class="form-group input-prepend <?= $crop->attribute ?>-input-update-alt-description">
                            <?= Html::label(FileModule::t('amosattachments', 'alt'), '', ['data-toggle'=>'tooltip','title'=>FileModule::t('amosattachments', '#alt_description_title'),'class' => 'btn btn-primary']) ?>
                            <?= Html::input('text', 'update_alt_desription['.$idFile.']', $val, ['style'=>'display:block','class' => 'form-control','placeholder'=>FileModule::t('amosattachments', '#alt_description')]) ?> 
                        </div>
                    </div>
            <?php endif; ?>
           
        <?php endif; ?>
        
        <div class="preview-pane <?= (!is_null($crop->defaultPreviewImage)) ? 'image-find' : '' ?>">
            <?php
            $closeButtonClass = is_null($crop->defaultPreviewImage) ? ' hidden d-none' : '';
            if (!$crop->hidePreviewDeleteButton) {
                echo Html::a(
                    AmosIcons::show('close', ['class' => '']),
                    'javascript:void(0)',
                    [
                        'class' => 'deleteImageCrop btn btn-danger btn-sm btn-icon' . $closeButtonClass,
                        'title' => FileModule::t('amosattachments', '#attach_delete_image_crop'),
                        'data' => [
                            'id' => $attachament ? $attachament->id : null,
                            'item_id' => $crop->model->id,
                            'model' => get_class($crop->model),
                            'attribute' => $crop->attribute
                        ]
                    ]
                );
            }
            ?>
            <div id="preview-container-<?= $crop->attribute ?>" class="preview-container">
                <?php
                if (!is_null($crop->defaultPreviewImage)) {
                    $defaultPreviewImageOptions = [
                        'id' => Yii::$app->getSecurity()->generateRandomString(10),
                        'width' => '100%',
                        'class' => 'preview_image',
                        'alt' => $crop->defaultPreviewImageAlt
                    ];
                    echo Html::img($crop->defaultPreviewImage, $defaultPreviewImageOptions);
                }

                ?>
            </div>
            
            <div id="preview-container-info-<?= $crop->attribute ?>" class="preview-info-container hide">
                <div class="size"></div>
                <div class="type text-right"></div>
            </div>
        </div>
        
        <?php if($enableMultipleUploads): ?>
        
            <div id="multiuploads-preview-<?= $crop->attribute ?>">
                
                <div class="preview-container" style="display:flex;flex-wrap: wrap;">
                    
                </div>
                
            </div>
        
        <div id="multiuploads-alt-descriptions-gallery-<?= $crop->attribute ?>" style="display:none"></div>
        
        <?php endif; ?>
        
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
                <div class="col-md-8">
                    <div class="cropper-wrapper"></div>
                </div>
                <!-- preview column -->
                <div class="col-md-4">
                    <div class="cropper-preview preview-lg"></div>

                    <div class="mt-3">
                        <div class="btn-group tools">
                            <button type="button" class="btn btn-primary rotate_<?= $crop->attribute ?>"
                                    data-type="rotate" data-option="-90" title="Rotate Left">
                            <span class="docs-tooltip" data-animation="false">
                                <?= AmosIcons::show('rotate-left', ['class' => 'am']); ?>
                            </span>
                            </button>
                            <button type="button" class="btn btn-primary rotate_<?= $crop->attribute ?>"
                                    data-type="rotate" data-option="90" title="Rotate Right">
                            <span class="docs-tooltip" data-animation="false">
                                <?= AmosIcons::show('rotate-right', ['class' => 'am']); ?>
                            </span>
                            </button>
                        </div>

                        <?php
                        if (isset($aspectRatioChoices) && !empty($aspectRatioChoices)) :
                            ?>
                            <div id="crop-buttons-id" class="btn-group tools">
                                <?php
                                foreach ($aspectRatioChoices as $choice) :
                                    ?>
                                    <button type="button"
                                            class="btn btn-xs btn-info aspectratio_<?= $crop->attribute ?>"
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
                        <div class="mt-3">
                            <p><?= FileModule::t('amosattachments', 'Si consiglia il caricamento di immagini orizzontali in proporzione 16:9 (dimensione consigliata minima: 1348x758px, ideale: 1920x1080px)') ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="col-xs-12 col-md-8" style="margin-top: 10px;display:<?= $enableAltDescription ? 'block' : 'none' ?>">                
                    <div class="form-group input-prepend">
                        <?= Html::label(FileModule::t('amosattachments', 'alt'), '', ['title'=>FileModule::t('amosattachments', '#alt_description_title'),'class' => 'btn btn-primary']) ?>            
                        <?= Html::input('text', 'alt_desription', '', ['class' => $crop->attribute.'_alt_description alt_description form-control','placeholder'=>FileModule::t('amosattachments', '#alt_description')]) ?>
                    </div>
                </div>

            </div>
    
            <?php yii\bootstrap4\Modal::end(); ?>

        <?php else : ?>
            <?php Modal::begin([
                'id' => 'cropper-modal-' . $crop->imageOptions['id'],
                'header' => '<h2>' . FileModule::t('amosattachments', '#crop_title') . '</h2>',
                'closeButton' => [],
                'footer' => '<div class="row cropper-btns">'
                    . Html::button(
                        FileModule::t('amosattachments', '#cancel_btn'),
                        ['id' => $crop->imageOptions['id'] . '_button_cancel', 'class' => 'btn btn-secondary', 'data-dismiss' => 'modal']
                    )
                    . Html::button(
                        FileModule::t('amosattachments', '#accept_btn'),
                        ['id' => $crop->imageOptions['id'] . '_button_accept', 'class' => 'btn btn-navigation-primary cropper-done']
                    ) . '</div>',
                'size' => Modal::SIZE_LARGE,
                'clientOptions' => ['backdrop' => 'static'] //To prevent closing when you drag outside the modal window
            ]);
            ?>
            <div id="image-source<?= $crop->imageOptions['id'] ?>" class="row cropper-body nom">
                <!-- Image crop area -->
                <div class="col-md-8">
                    <div class="cropper-wrapper"></div>
                </div>
                <!-- preview column -->
                <div class="col-md-4">
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
                
                <div class="col-xs-12 col-md-8" style="margin-top: 10px;display:<?= $enableAltDescription ? 'block' : 'none' ?>">                
                    <div class="form-group input-prepend">
                        <?= Html::label(FileModule::t('amosattachments', 'alt'), '', ['title'=>FileModule::t('amosattachments', '#alt_description_title'),'class' => 'btn btn-primary']) ?>            
                        <?= Html::input('text', 'alt_desription', '', ['class' => $crop->attribute.'_alt_description alt_description form-control','placeholder'=>FileModule::t('amosattachments', '#alt_description')]) ?>
                    </div>
                </div>

            </div>    
            
            <?php Modal::end(); ?>

        <?php endif; ?>
     
    </div>

<?php

//
//$css = <<<CSS
//.cropper-alert + .cropper-body {
//    display: none;
//}
// .cropper-alert{
//        font-size: 0px;
//    }
//    .cropper-alert::after{
//        content: "$alertString";
//        font-size:15px;
//        margin-left: 60px;
//}
//CSS;

$css = <<<CSS
.cropper-alert + .cropper-body {
    display: none;
}
    .cropper-alert::after{
        content: "$alertString" !important;
        font-size:15px;
}
        
#cropper-modal-{$crop->imageOptions['id']} .modal-body .{$crop->attribute}_alt_description{
    display:block;
}
    
#crop-dropdown-container-id-{$crop->attribute} .file-caption input{
    display: block;
}
CSS;

$this->registerCss($css);

$js = <<<JS
jQuery(document).on('shown.bs.modal', '#$modalId' , function() {      
  
});
        
jQuery(document).on('hidden.bs.modal', '#$modalId' , function() {      
    $('#$formId').yiiActiveForm('validateAttribute', '$inputId');
});
        
  //On delete button click
jQuery('.deleteImageCrop:not(#multiuploads-preview-{$crop->attribute} .deleteImageCrop)', '#{$cropperInputId}').on('click', function() {
    //Metadata
    var data = jQuery(this).data();
    
    //Hide the button
    jQuery(this).addClass('hidden');
    jQuery(this).addClass('d-none');
  
    //Remove the image
    jQuery('#preview-container-{$crop->attribute} img:not(#multiuploads-preview-{$crop->attribute} img), canvas').remove();
    
    //Clear crop if exists
    jQuery('.cropper-data').attr('val', '');
    jQuery('#{$inputId}').val('');
    
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
    jQuery('.deleteImageCrop', '#{$cropperInputId}').removeClass('d-none');
        
    let image = $('.modal-body .cropper-wrapper>img', '#{$cropperInputId}');
    let box = document.querySelector('#{$inputId}');
    let width = box.offsetWidth;
    let mycanvas = image.cropper('getCroppedCanvas', { width: width });
    if (mycanvas) {
        jQuery('#preview-container-{$cropperInputId}').html(mycanvas);
    }
      
    //add crop data input
    var cropData = jQuery('input[name="{$crop->attribute}_data"]').val();
    var add_input = jQuery('<input type="hidden" class="" name="{$crop->attribute}_crop_info[]">').val(cropData);    
          
    jQuery('.{$crop->attribute}_data_info .crop', '#{$cropperInputId}').append(add_input);
     
    //add source input
    var source = jQuery('#uploaded-from-source').val();
    var add_input = jQuery('<input type="hidden" class="" name="{$crop->attribute}_source[]">').val(source);    

    jQuery('.{$crop->attribute}_data_info .source', '#{$cropperInputId}').append(add_input);
    
    //add alt description
    var source = jQuery('#cropper-modal-{$crop->imageOptions['id']} .modal-body .{$crop->attribute}_alt_description', '#{$cropperInputId}').val();
    var add_input = jQuery('<input type="hidden" class="" name="{$crop->attribute}_alt_description[]">').val(source);    

    jQuery('.{$crop->attribute}_data_info .alt_description', '#{$cropperInputId}').append(add_input);
    
    var files = $filesUploaded;

    if(files.length > 0){
        let file = files[0];
        let size = file.size >= 1024 * 1024 ? (file.size / (1024 * 1024)).toFixed(2) + ' MB' : (file.size / 1024).toFixed(2) + ' KB';
        jQuery('#preview-container-info-{$crop->attribute} .size').html(size);
        jQuery('#preview-container-info-{$crop->attribute} .type').html(file.type);
        jQuery('#preview-container-info-{$crop->attribute}').toggleClass('hide');
            
    }
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


if($enableMultipleUploads){

$dataTransfer = 'Dt_'.str_replace('-','_',$crop->imageOptions['id']); 
$jsMultiUploads = <<<JS
        
    var $dataTransfer = new DataTransfer();

    jQuery('#{$cropperInputId} > .preview-pane').hide();
        
    jQuery(document).on('shown.bs.modal', '#$modalId' , function() {      
    
        jQuery(this).find('.modal-footer button[class*="cropper-done"]').removeClass('hide');
        if(chechMaxFile$dataTransfer()){           
            jQuery(this).find('.modal-footer button[class*="cropper-done"]').addClass('hide');
        }
     
    });
    
    jQuery(document).on('hidden.bs.modal', '#$modalId' , function() {      
        
        var filecaption = jQuery('#crop-dropdown-container-id-{$crop->attribute} .file-caption-name');
        var fileName = '';
        if($dataTransfer.files.length > 1)
            fileName = $dataTransfer.files.length + ' $selection_label';
        else if($dataTransfer.files.length == 1){
            fileName = $dataTransfer.files[0].name;
        }    
        jQuery(filecaption).val(fileName);
            
    });
    
    jQuery(document).on('click', '#{$cropperInputId} #multiuploads-preview-{$crop->attribute} .deleteImageCrop' ,function() {
    
        var pic_container = jQuery(this).closest('.preview-pane');
        var index = pic_container.index();
 
        jQuery(this).parent().remove();
        deleteItem$dataTransfer(index);
           
        $('#$formId').yiiActiveForm('validateAttribute', '$inputId');
    });
    
    jQuery('.modal-footer button[class*="cropper-done"]', '#{$cropperInputId}').on('click', function() {
     
        var files = $filesUploaded;
        var empty = new DataTransfer();
        document.getElementById('$inputId').files = empty.files;
                 
        jQuery('.modal-backdrop').remove();
                   
        //append multiple files
        if(files.length > 0){
        
            var file = files[0];                  
            
            $dataTransfer.items.add(file);
                
            updateInputFile$dataTransfer();

            var clone = jQuery('#{$cropperInputId} > .preview-pane').clone(true);
            
            clone.find('#preview-container-info-{$crop->attribute}').removeAttr('id').removeClass('hide');
            clone.appendTo('#multiuploads-preview-{$crop->attribute} .preview-container').show();

            $filesUploaded = Array.prototype.slice.call(files, 1);
            $('#multiuploads-alt-descriptions-gallery-{$crop->attribute}').find('span').eq(0).remove();
                       
            if($filesUploaded.length > 0)
                loadNextFile$dataTransfer();
          
        }
            
   
    });
        
    jQuery('#{$closeButtonModalId}, #{$cropperInputId} button.close').click(function(e){ 
        
        let files = $filesUploaded;
        var empty = new DataTransfer();
        document.getElementById('$inputId').files = empty.files;
            
        jQuery('.modal-backdrop').remove();
            
        updateInputFile$dataTransfer();
            
        //append multiple files
        if(files.length > 0){
            
            var file = files[0];           
            
            $filesUploaded = Array.prototype.slice.call(files, 1); 
            $('#multiuploads-alt-descriptions-gallery-{$crop->attribute}').find('span').eq(0).remove();
            
            
            if($filesUploaded.length > 0)
                loadNextFile$dataTransfer();
               
        }    
          
    });
  
    function deleteItem$dataTransfer(index) {
        $dataTransfer.items.remove(index);
            
        jQuery('#{$cropperInputId} .{$crop->attribute}_data_info .crop').find('input').eq(index).remove();
        jQuery('#{$cropperInputId} .{$crop->attribute}_data_info .source').find('input').eq(index).remove();
        jQuery('#{$cropperInputId} .{$crop->attribute}_data_info .alt_description').find('input').eq(index).remove();
            
        updateInputFile$dataTransfer();
    }
   
    function clear$dataTransfer() {
        $dataTransfer.items.clear();
        updateInputFile$dataTransfer();
    }
        
    function updateInputFile$dataTransfer() {
        
        document.getElementById('$inputId').files = $dataTransfer.files;
            
        var filecaption = jQuery('#crop-dropdown-container-id-$crop->attribute .file-caption-name');
        var fileName = '';
        if($dataTransfer.files.length > 1)
            fileName = $dataTransfer.files.length + ' $selection_label';
        else if($dataTransfer.files.length == 1){
            fileName = $dataTransfer.files[0].name;
        }    
        jQuery(filecaption).val(fileName);
                
        console.log('files',jQuery('#$inputId').prop('files'));
    }
                
    function loadNextFile$dataTransfer() {
                   
        if(chechMaxFile$dataTransfer())
            return;
                
        setTimeout(function() {
            
            let files = $filesUploaded;
            let nextfile = files[0];

            jQuery('.modal-backdrop').remove();       

            const reader = new FileReader();
            reader.onload = function(e) { 

                 //Trigger cropping       
                let list = new DataTransfer();
                files.forEach(function(file) {
                    list.items.add(file);
                });

                //jQuery('#attach-gallery-{$crop->attribute}').modal('hide');      
                jQuery('.uploadcrop.attachment-uploadcrop').removeClass('cropper-done'); 

                jQuery('#crop-input-container-id-{$crop->attribute} input[type="file"]').prop('files', list.files);
                jQuery('#crop-input-container-id-{$crop->attribute} input[type="file"]').trigger('change');

            }
            reader.readAsDataURL(nextfile); 
            
        }, 500);
        
    }
                
    function chechMaxFile$dataTransfer() {
        if($dataTransfer.files.length >= $maxMultipleUploads){
                        
            const error_msg = '$max_upload_file_error';
            if (typeof krajeeDialog != "undefined") {
                krajeeDialog.alert(error_msg);
            }else
                alert(error_msg);
                            
            return true;
        }else
            return false;
    }
                    

JS;
    
$this->registerJs($jsMultiUploads, \yii\web\View::POS_READY);

}else{
$jsSinlgeUpload = "
        
jQuery('#{$closeButtonModalId}, #{$cropperInputId} button.close').click(function(e){   
    jQuery('#$inputId').val(null);
        
    //show text update alt description if exists
    jQuery('.{$crop->attribute}-input-update-alt-description').show();
        
    jQuery('#crop-dropdown-container-id-$crop->attribute .file-caption-name').val('');
    
});
";  
$this->registerJs($jsSinlgeUpload, \yii\web\View::POS_READY);
}
?>
