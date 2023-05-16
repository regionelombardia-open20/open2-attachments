<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\components\views
 * @category   CategoryName
 */

use open20\amos\attachments\FileModule;
use open20\amos\core\forms\ActiveForm;

if (preg_match('/^[a-zA-Z_]+$/', $attribute) == 0) {
    $attribute = '';
}
$modelNamespace = explode('\\',get_class($modelSearch));
$classModel  = end($modelNamespace);

$csrfParam = \Yii::$app->request->csrfParam;
$this->registerJsVar('pageSize', 20);
$js = <<<JS
    // Add the preview of the image and set in session the id of the file to attach
    $(document).on('click','.link-image' , function(e){
        e.preventDefault();

        var id_image = $(this).attr('data-key');
        var attribute = $(this).attr('data-attribute');
        var src_image = $(this).find('img').attr('src');
        var tag_img = "<img class='img-responsive' src='"+src_image+"' style='display:block;width:100%;height:auto; 'min-width:0!important;min-height:0!important; max-width:none!important;max-height:none!important; image-orientation:0deg!important;'>";


        $('#container-preview-image-'+attribute).load('/attachments/attach-gallery-image/load-detail?id_image='+id_image+'&attribute='+attribute, function () {
            $('#container-preview-image-'+attribute).show();
            $('#container-gallery-'+attribute).hide();
        //         $('.loading').hide();
            });
        return;
    });

    // delete the id of the file from session
    function deleteFromSession(){
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
    }

    //event on click delete preview
    $(document).on('click', '.deleteImageCrop', function(){
        deleteFromSession();
    });

    //event on change image (if you upload the image normally for example)
    $(document).on('change', '.cropper-data', function(){
         deleteFromSession();
    });

    //ESCI DA FULLSCREEN
    $(document).on('click', '.exit-fullscreen-btn', function(e){
        e.preventDefault();
        var attribute = $(this).attr('data-attribute');
          $('#container-preview-image-'+attribute).hide();
          $('#container-gallery-'+attribute).show();
    });

    
    //SELEZIONA IMMAGINE
    $(document).on('click','#image-gallery-link-$attribute, .select-image-$attribute', function(e){
        e.preventDefault();
         var csrf = $('form input[name="$csrfParam"]').val();

        var id_image = $(this).attr('data-key');
        var attribute = $(this).attr('data-attribute');
        var src_image = $(this).attr('data-src');
        var filename = $(this).attr('data-filename');
        var tag_img = "<img class='img-responsive' src='"+src_image+"' style='display:block;width:100%;height:auto; 'min-width:0!important;min-height:0!important; max-width:none!important;max-height:none!important; image-orientation:0deg!important;'>";
          $.ajax({
          method: 'get',
          url: "/attachments/attach-gallery-image/upload-from-gallery-ajax",
          data: {id: id_image, attribute: attribute,  csrf: csrf},
          success: function(data){
              if(data.success == true){
                $('.preview-pane .hidden').removeClass('hidden');
                $('#event-eventlogo').val('');
                $('#crop-input-container-id-$attribute input[type="file"]').val('');
                //set filename on crop input
                var filecaption = $('#crop-dropdown-container-id-$attribute .file-caption-name');
                $(filecaption).val(filename);
                
                //for crop input
                $('#preview-container-'+attribute).html(tag_img);
                
                //for attchmentsinput
                $('#container-preview-'+attribute+' .file-preview-image').each(function(){
                    $(this).attr('src', src_image);
                });
                
                 //Trigger cropping
                getFileFromUrl(src_image, '$classModel'+'_'+'$attribute')
                .then(function(file) {
                    let list = new DataTransfer();
                    list.items.add(file);
                
                    $('#crop-input-container-id-$attribute input[type="file"]').prop('files', list.files);
                    $('#crop-input-container-id-$attribute input[type="file"]').trigger('change');
                });
                
                $('#attach-gallery-'+attribute).modal('hide');
                $('#uploaded-from-source').val('upload_from_gallery');
                $('.uploadcrop.attachment-uploadcrop').addClass('cropper-done');
                }
          }
        });
    });
    
  

JS;

$this->registerJs($js);

$js2 = <<<JS
   shimmerImage();

  function showImageLoaded(image){
       var image = $(image).parents('.content-item');
       $(image).find('.placeholder-image').remove();
       $(image).find('.open-modal-detail-btn').show();   
    }
    //hide placeholder-image after real image is loaded
    function shimmerImage(){
        $('.content-item .open-modal-detail-btn img').each(function() {
            if( this.complete ) {
               showImageLoaded(this);
           } else {
                $(this).one('load', function(){
                    showImageLoaded(this);
                });
           }
        });
    }
 
    //PAGINAZIONE
  $(document).on('click', '#image-gallery-$attribute .pagination a', function(e){
        e.preventDefault();
        let dataPage = parseInt($(this).attr('data-page')) +1;
         $.ajax({
          method: 'get',
          url: "/attachments/attach-gallery-image/search-gallery-ajax",
          data: $('#form-gallery-$attribute').serialize()+'&attribute=$attribute&page='+dataPage+'&per-page='+pageSize,
          success: function(data){
                $('#image-gallery-$attribute').html(data);
                shimmerImage();
                }
        });
  });
    //RICERCA
    $(document).on('click', '#btn-search-gallery-$attribute', function(e){
        e.preventDefault();
         $.ajax({
          method: 'get',
          url: "/attachments/attach-gallery-image/search-gallery-ajax",
          data: $('#form-gallery-$attribute').serialize()+'&attribute=$attribute',
          success: function(data){
                $('#image-gallery-$attribute').html(data);
                shimmerImage();
          }
        }); 
    });
    
//CANCELLA RICERCA
 $(document).on('click', '#btn-cancel-gallery-$attribute', function(e){
        e.preventDefault();
        var inputs = $('.content-search-gallery').find('input[type="text"]');
        $(inputs).each(function(){
            $(this).val('');
        });
        $('.content-search-gallery').find('select').each(function(){
            $(this).val('');
            $(this).trigger('change');
        });
        $('#custom-tags-search-id-$attribute').tagit("removeAll");
        
         $.ajax({
          method: 'get',
          url: "/attachments/attach-gallery-image/search-gallery-ajax",
          data: {attribute: '$attribute'},
          success: function(data){
                $('#image-gallery-$attribute').html(data);
                 shimmerImage();
                }
        }); 
    });
 
 $(document).on('autocompleteresponse', '#form-gallery-$attribute .tagit-new input', function(){
    $('.ui-menu.ui-widget-content.ui-autocomplete').each(function(){
        $(this).show();
    });
 });

JS;

$this->registerJs($js2);
?>

<div id="container-gallery-<?= $attribute ?>">
    <?php $modelSearch = new \open20\amos\attachments\models\search\AttachGalleryImageSearch(); ?>
    <?= $this->render('_search_gallery_view', [
        'modelSearch' => $modelSearch,
        'attribute' => $attribute
    ]) ?>

    <div id="image-gallery-<?= $attribute ?>">
        <?php
        $dataProvider = $modelSearch->searchGenericFiles([], $gallery->id);
        $images = $gallery->getAttachGalleryImages()->all();
        echo $this->render('images_gallery', [
            'images' => $images,
            'attribute' => $attribute,
            'dataProvider' => $dataProvider
        ]) ?>
    </div>
</div>

<div style="display:none" id="container-preview-image-<?= $attribute ?>">

</div>



