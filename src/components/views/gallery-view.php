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

$multiImage = $multiSelection ? 1 : 0;
$filesUploadedAltDescriptionFromGallery = 'files_uploaded_alt_description_'.$attribute; 

$no_selection = FileModule::t('amosattachments', '#no_selected_image');

$csrfParam = \Yii::$app->request->csrfParam;
$this->registerJsVar('pageSize', 20);
$js = <<<JS
    var multi_selection_$attribute = $multiImage;
    var selected_image_ids_$attribute = [];
     
    // Add the preview of the image and set in session the id of the file to attach
    $(document).off('click', '.link-image').on('click','.link-image' , function(e){
        e.preventDefault();
       
        var id_image = $(this).attr('data-key');
        var attribute = $(this).attr('data-attribute');
        var src_image = $(this).find('img').attr('src');
        var tag_img = "<img class='img-responsive' src='"+src_image+"' style='display:block;width:100%;height:auto; 'min-width:0!important;min-height:0!important; max-width:none!important;max-height:none!important; image-orientation:0deg!important;'>";


        $('#container-preview-image-'+attribute).load('/attachments/attach-gallery-image/load-detail?id_image='+id_image+'&attribute='+attribute+'&multiSelection={$multiSelection}', function () {
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
    $(document).off('click', '.deleteImageCrop').on('click', '.deleteImageCrop', function(){
        deleteFromSession();
    });

    //event on change image (if you upload the image normally for example)
    $(document).off('change', '.cropper-data').on('change', '.cropper-data', function(){
         deleteFromSession();
    });

    //ESCI DA FULLSCREEN
    $(document).off('click', '.exit-fullscreen-btn').on('click', '.exit-fullscreen-btn', function(e){
        e.preventDefault();
        var attribute = $(this).attr('data-attribute');
          $('#container-preview-image-'+attribute).hide();
          $('#container-gallery-'+attribute).show();
    });

    if(multi_selection_$attribute){
        
        $(document).off('click', '.select-multimage-$attribute').on('click', '.select-multimage-$attribute', function(e){

            $(this).toggleClass('mdi-checkbox-blank-outline mdi-checkbox-marked');
                
            let image_id = $(this).data('key');
                
            if($(this).hasClass('mdi-checkbox-marked')){
                selected_image_ids_$attribute.push(image_id); 
            }else{
                const index = selected_image_ids_$attribute.indexOf(image_id);
                if (index > -1) {
                    selected_image_ids_$attribute.splice(index, 1);
                }
            }
                
            // AGGIORNO LE INFO NEL MODAL FOOTER
            $('#label-selezionati-$attribute .count').html(selected_image_ids_$attribute.length);
                
            $('#selected-files-$attribute').html('');
            $.each(selected_image_ids_$attribute, function( index, value ) {
                let name = $('.select-multimage-{$attribute}[data-key="' + value + '"]').data('name');
   
                let row = $('<li>' + name + '</li>');
                $('#selected-files-$attribute').append(row);
            });
            
                
            //console.log('images',selected_image_ids_$attribute);
        });
                
        //SELEZIONA IMMAGINE MULTIPLA
        $(document).off('click','#insert-images-btn-$attribute').on('click','#insert-images-btn-$attribute', function(e){
            e.preventDefault();
            
            if(selected_image_ids_$attribute.length > 0){
                $(this).addClass('disabled'); 
                $(this).find('.mdi-loading').removeClass('hide');
            }
            
            if(selected_image_ids_$attribute.length == 0){
                alert("{$no_selection}");
                return;
            }
                    
            var csrf = $('form input[name="$csrfParam"]').val();           
            var promises = [];
                        
            $.ajax({
                method: 'post',
                url: '/attachments/attach-gallery-image/multi-upload-from-gallery-ajax?attribute=$attribute'+'&csrf='+csrf,
                data: {ids: selected_image_ids_$attribute},
                success: function(data){
                    if(data.success == true){
                            
                        let list = new DataTransfer();
                        
                        $.each(data.data, function( index, obj ) {
   
                            //Trigger cropping
                            let promise = getFileFromUrl(obj.src, obj.name)
                            .then(function(file) {
                                list.items.add(file);
                                $('#multiuploads-alt-descriptions-gallery-$attribute').append('<span>'+obj.alt_description+'</span>');
                            });
                                        
                            promises.push(promise);
                  
                        });
                                        
                        Promise.all(promises).then(function() {
                                
                                $('#crop-input-container-id-$attribute input[type="file"]').prop('files', list.files);
                                $('#crop-input-container-id-$attribute input[type="file"]').trigger('change');
                                    
                                $('#attach-gallery-$attribute').modal('hide');
                        })
                        .catch(function(error) {
                            alert("Si è verificato un errore durante l'elaborazione:", error);
                        });
          
                        $('#uploaded-from-source').val('upload_from_gallery');
                        $('.uploadcrop.attachment-uploadcrop').addClass('cropper-done');
                    }
                }
            });
        });
             
                        
                        
    }else{
        
        //SELEZIONA IMMAGINE SINGOLA
        $(document).off('click','#image-gallery-link-$attribute, .select-image-$attribute').on('click','#image-gallery-link-$attribute, .select-image-$attribute', function(e){
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

                        $('.{$attribute}_alt_description').val(data.alt_description);
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
                                
    }
  

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
     
    //in base alla ricerca o alla paginazione rimette i check alle immagini già selezionate   
    function autoCheckImages(){
        if(multi_selection_$attribute){
            
            $.each(selected_image_ids_$attribute, function( index, value ) {
                let name = $('.select-multimage-{$attribute}[data-key="' + value + '"]').toggleClass('mdi-checkbox-blank-outline mdi-checkbox-marked');
            });
        
        }
    }
 
    //PAGINAZIONE
  $(document).off('click', '#image-gallery-$attribute .pagination a').on('click', '#image-gallery-$attribute .pagination a', function(e){
        e.preventDefault();
        let dataPage = parseInt($(this).attr('data-page')) +1;
        $.ajax({
            method: 'get',
            url: "/attachments/attach-gallery-image/search-gallery-ajax",
            data: $('#form-gallery-$attribute').serialize()+'&attribute=$attribute&page='+dataPage+'&per-page='+pageSize+'&multiSelection={$multiSelection}',
            success: function(data){
                $('#image-gallery-$attribute').html(data);
                shimmerImage();
                autoCheckImages();
            }
        });
  });
    //RICERCA
    $(document).off('click', '#btn-search-gallery-$attribute').on('click', '#btn-search-gallery-$attribute', function(e){
        e.preventDefault();
         $.ajax({
          method: 'get',
          url: "/attachments/attach-gallery-image/search-gallery-ajax",
          data: $('#form-gallery-$attribute').serialize()+'&attribute=$attribute&multiSelection={$multiSelection}',
          success: function(data){
                $('#image-gallery-$attribute').html(data);
                shimmerImage();
                autoCheckImages();
          }
        }); 
    });
    
//CANCELLA RICERCA
 $(document).off('click', '#btn-cancel-gallery-$attribute').on('click', '#btn-cancel-gallery-$attribute', function(e){
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
            url: "/attachments/attach-gallery-image/search-gallery-ajax?multiSelection={$multiSelection}",
            data: {attribute: '$attribute'},
            success: function(data){
                $('#image-gallery-$attribute').html(data);
                shimmerImage(); 
                autoCheckImages();
            }
        }); 
    });
 
 $(document).off('autocompleteresponse', '#form-gallery-$attribute .tagit-new input').on('autocompleteresponse', '#form-gallery-$attribute .tagit-new input', function(){
    $('.ui-menu.ui-widget-content.ui-autocomplete').each(function(){
        $(this).show();
    });
 });

JS;

$this->registerJs($js2);
?>

<div id="container-gallery-<?= $attribute ?>">
    <?php
    $module = \Yii::$app->getModule('attachments');
    $modelSearch = $module->createModel('AttachGalleryImageSearch');
    ?>
    <?= $this->render('_search_gallery_view', [
        'modelSearch' => $modelSearch,
        'attribute' => $attribute,
        'multiSelection' => $multiSelection
    ]) ?>

    <div id="image-gallery-<?= $attribute ?>">
        <?php
        $dataProvider = $modelSearch->searchGenericFiles([], $gallery->id);
        $images = $gallery->getAttachGalleryImages()->all();
        echo $this->render('images_gallery', [
            'images' => $images,
            'attribute' => $attribute,
            'dataProvider' => $dataProvider,
            'multiSelection' => $multiSelection
        ]) ?>
    </div>
    
</div>

<div style="display:none" id="container-preview-image-<?= $attribute ?>">

</div>



