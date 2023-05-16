<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\components\views
 * @category   CategoryName
 *
 * @var $attribute string
 * @var $dataProvider \yii\data\ArrayDataProvider
 * @var $modelSearch \open20\amos\attachments\models\Shutterstock
 * @var $this \yii\web\View
 */

use open20\amos\attachments\FileModule;
use open20\amos\core\forms\ActiveForm;

if (preg_match('/^[a-zA-Z_]+$/', $attribute) == 0) {
    $attribute = '';
}

\open20\amos\attachments\assets\ModuleAttachmentsAsset::register($this);

$modelNamespace = explode('\\',get_class($modelSearch));
$classModel  = end($modelNamespace);
$csrfParam = \Yii::$app->request->csrfParam;
$this->registerJsVar('pageSize', 20);
$js = <<<JS

if(!loadedOnce){
    loadedOnce = 1;
    // Add the preview of the image and set in session the id of the file to attach
    $(document).on('click','.link-shutterstock' , function(e){
        e.preventDefault();
        var id_image = $(this).attr('data-key');
        var attribute = $(this).attr('data-attribute');
    
        $('#container-preview-shutterstock-'+attribute).load('/attachments/shutterstock/load-detail?id_image='+id_image+'&attribute='+attribute, function () {
            $('#container-preview-shutterstock-'+attribute).show();
            $('#container-shutterstock-'+attribute).hide();
        //         $('.loading').hide();
            });
        return;
    });
    
    // delete the id of the file from session
    function deleteFromSession(){
         var csrf = $('form input[name="$csrfParam"]').val();

         $.ajax({
          method: 'get',
          url: "/attachments/shutterstock/delete-from-session-ajax",
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

        //ESCI DAL DETTAGLIO MODALE
    $(document).on('click', '.exit-fullscreen-btn-shutterstock-$attribute', function(e){
        e.preventDefault();
        var attribute = $(this).attr('data-attribute');
          $('#container-preview-shutterstock-'+attribute).hide();
          $('#container-shutterstock-'+attribute).show();
    });

        //SELEZIONA IMMAGINE DI SHUTTERSTOCK
    $(document).on('click','#image-shutterstock-link-$attribute, .select-image-shutterstock-$attribute', function(e){
        e.preventDefault();
        $('.loading').show();
        var csrf = $('form input[name="$csrfParam"]').val();
         
        var id_image = $(this).attr('data-key');
        var proscription_type = $('#licenceImageType-id-$attribute').val();
        var attribute = $(this).attr('data-attribute');
        var src_image = $(this).attr('data-src');
        var name_image = $(this).attr('data-name');
        var tag_img = "<img class='img-responsive' src='"+src_image+"' style='display:block;width:100%;height:auto; 'min-width:0!important;min-height:0!important; max-width:none!important;max-height:none!important; image-orientation:0deg!important;'>";
          $.ajax({
          method: 'get',
          url: "/attachments/shutterstock/upload-from-gallery-ajax",
          data: {id: id_image, proscription_type: proscription_type, attribute: attribute, name:name_image, csrf: csrf},
          success: function(data){
            $('.loading').hide();
            if(data.success == true){
                //recupero immagine vera da croppare
                if(data.urlDownload){
                    src_image = data.urlDownload;
                    tag_img = "<img class='img-responsive' src='"+src_image+"' style='display:block;width:100%;height:auto; 'min-width:0!important;min-height:0!important; max-width:none!important;max-height:none!important; image-orientation:0deg!important;'>";
                }
                
                $('.preview-pane .hidden').removeClass('hidden');
                $('#crop-input-container-id-$attribute input[type="file"]').val('');
                //set filename on crop input
                var filecaption = $('#crop-dropdown-container-id-$attribute .file-caption-name');
                $(filecaption).val(name_image);
                
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
                
                $('#uploaded-from-source').val('upload_from_shutterstock');
                $('#attach-shutterstock-'+attribute).modal('hide');
                $('.uploadcrop.attachment-uploadcrop').addClass('cropper-done');
                $('#id-shutterstock_file_is_selected_'+attribute).val(1);
              } else {
                  console.log(data.data);
                  alert("Si è verificato un erore, riprova più tardi");
             }
          },
          error: function(data){
              $('.loading').hide();
              alert("Si è verificato un erore, riprova più tardi");
          }
        });
    });
}
JS;

$this->registerJs($js);

$js2 = <<<JS
  
 
    //PAGINAZIONE
  $(document).on('click', '#image-shutterstock-$attribute .pagination a', function(e){
        e.preventDefault();
        let dataPage = parseInt($(this).attr('data-page'))+1;
         $.ajax({
          method: 'get',
          url: "/attachments/shutterstock/search-shutterstock-ajax",
          data: $('#form-shutterstock-$attribute').serialize()+'&attribute=$attribute&page='+dataPage+'&per-page='+pageSize,
          success: function(data){
                $('#image-shutterstock-$attribute').html(data);
                }
        });
  });
    //RICERCA
    $(document).on('click', '#btn-search-shutterstock-$attribute', function(e){
        e.preventDefault();
         $.ajax({
          method: 'get',
          url: "/attachments/shutterstock/search-shutterstock-ajax",
          data: $('#form-shutterstock-$attribute').serialize()+'&attribute=$attribute',
          success: function(data){
                $('#image-shutterstock-$attribute').html(data);
          }
        }); 
    });
    
//CANCELLA RICERCA
 $(document).on('click', '#btn-cancel-shutterstock-$attribute', function(e){
        e.preventDefault();
        var inputs = $('.content-search-shutterstock').find('input[type="text"]');
        $(inputs).each(function(){
            $(this).val('');
        });
        $('.content-search-shutterstock').find('select').each(function(){
            $(this).val('');
            $(this).trigger('change');
        });
        
         $.ajax({
          method: 'get',
          url: "/attachments/shutterstock/search-shutterstock-ajax",
          data: {attribute: '$attribute'},
          success: function(data){
                $('#image-shutterstock-$attribute').html(data);
                }
        }); 
    });
 

 //SUGGERIMENTI RICERCA
//  $(document).on('keydown', '#id-query-attribute', function(){
//        let query = $(this).val();
//        console.log(query);
//         $.ajax({
//          method: 'get',
//          url: "/attachments/shutterstock/suggestions-image-ajax",
//          data: {query: query},
//          success: function(data){
//                var suggestions = data.data;
//                $('#suggestions-attribute ul').html('');
//                $(suggestions).each(function (){
//                    $('#suggestions-attribute ul').append("<li><a>"+this+"</a></li>")
//                });
//                $('#suggestions-attribute').show();
//            }
//        }); 
//    });
 

JS;

$this->registerJs($js2);
?>

<div id="container-shutterstock-<?= $attribute ?>">
    <?= $this->render('_search_shutterstock', [
        'modelSearch' => $modelSearch,
        'attribute' => $attribute
    ]) ?>

    <div id="image-shutterstock-<?= $attribute ?>">
        <?php
        echo $this->render('shutterstock_items', [
            'attribute' => $attribute,
            'dataProvider' => $dataProvider,
            'errorMessage' => $errorMessage,
            'firstLoad' => $firstLoad
        ]) ?>
    </div>
</div>

<div style="display:none" id="container-preview-shutterstock-<?= $attribute ?>"></div>



