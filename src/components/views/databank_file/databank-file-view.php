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

$csrfParam = \Yii::$app->request->csrfParam;
$this->registerJsVar('labelFileSelezionati', FileModule::t('amosattachments', 'File selezionati'));

$js = <<<JS
    let selected_file_ids = [];
    let selected_file_names = {};

if(!loadedOnce){
    loadedOnce = 1;
    
    //ON OPEN MODAL
    $(document).on('click', '.open-modal-databank-file', function (event) {
        resetFileCaption();
        selected_file_ids = [];
        selected_file_names = {};
        var parent = $('#attach-databank-file-$attribute').parent();
        var filecaption = $(parent).find('.file-caption-name');
        $(filecaption).val('');
     });
    
    // MOSTRA DETTAGLIO
    $(document).on('click','.show-detail-file' , function(e){
        e.preventDefault();
        var id_file = $(this).attr('data-key');
        var attribute = $(this).attr('data-attribute');

        $('#container-preview-file-'+attribute).load('/attachments/attach-databank-file/load-detail?id_file='+id_file+'&attribute='+attribute, function () {
            $('#container-preview-file-'+attribute).show();
            $('#container-databank-file-'+attribute).hide();
        //         $('.loading').hide();
            });
        return;
    });

    // delete the id of the file from session
    function deleteFromSession(){
         var csrf = $('form input[name="$csrfParam"]').val();

         $.ajax({
          method: 'get',
          url: "/attachments/attach-databank-file/delete-from-session-ajax",
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

    $(document).on('click', '.exit-fullscreen-btn', function(e){
        e.preventDefault();
        var attribute = $(this).attr('data-attribute');
          $('#container-preview-file-'+attribute).hide();
          $('#container-databank-file-'+attribute).show();
    });
    
    function addRemoveItemSelected(item_file, id_file, attribute, name){
         if($(item_file).hasClass('file-item-selected')){
              let checkbox = $(item_file).find('.checkbox-selection');
            $(checkbox).addClass('mdi-checkbox-blank-outline');
            $(checkbox).removeClass('mdi-checkbox-marked');
            $(item_file).removeClass('file-item-selected');
            const index = selected_file_ids.indexOf(id_file);
            if (index > -1) {
                selected_file_ids.splice(index, 1);
            }
            $("#sel-file-"+attribute+"-"+id_file).remove();
            $('#label-selezionati-'+attribute).text(labelFileSelezionati+" ("+selected_file_ids.length+")");
            delete selected_file_names[id_file];
        }else{
            $(item_file).addClass('file-item-selected');
            selected_file_ids.push(id_file);
            selected_file_names[id_file] = name;
            $('#selected-files-'+attribute).append("<li style='border-bottom: 1px solid #dddddd;padding: 3px 0;' id='sel-file-"+attribute+"-"+id_file+"'>"+name+"</li>");
            $('#label-selezionati-'+attribute).text(labelFileSelezionati+" ("+selected_file_ids.length+")");
            let checkbox = $(item_file).find('.checkbox-selection');
            $(checkbox).removeClass('mdi-checkbox-blank-outline');
            $(checkbox).addClass('mdi-checkbox-marked');
        }
         //console.log(selected_file_names);
    }

    // SELEZIONA FILE
    $(document).on('click','.select-file-$attribute', function(e){
        e.preventDefault();
        var csrf = $('form input[name="$csrfParam"]').val();
        var id_file = $(this).attr('data-key');
        var attribute = $(this).attr('data-attribute');
        var name = $(this).attr('data-name');
        var item_file = $('#content-item-id-'+attribute+'-'+id_file);
        
        addRemoveItemSelected(item_file, id_file, attribute, name);
    });
    
    
    
    function resetFileCaption(){
           var parent = $('#attach-databank-file-$attribute').parent();
           var filecaption = $(parent).find('.file-caption-name');
           $(filecaption).attr('placeholder', "Seleziona file ...");
     }
     
    // INSERISCI FILE SELEZIONATI
     $(document).on('click', '#insert-files-btn-$attribute', function(e){
         e.preventDefault();
         if(selected_file_ids.length == 0){
             alert('Non hai selezionato alcun file');
             return true;
         }
         let file_ids = selected_file_ids.join();
         var csrf = $('form input[name="$csrfParam"]').val();
                 
         // console.log(file_ids);
         $.ajax({
          method: 'get',
          url: "/attachments/attach-databank-file/upload-from-databank-file-ajax",
          data: {file_ids: file_ids, attribute: '$attribute',  csrf: csrf},
          success: function(data){
              if(data.success == true){
                  var parent = $('#attach-databank-file-$attribute').parent();
                  var filecaption = $(parent).find('.file-caption-name');

                 if(selected_file_ids.length == 0){
                     resetFileCaption();
                 }else if(selected_file_ids.length == 1){
                       $(filecaption).val(Object.values(selected_file_names)[0]);
                 }else {
                       $(filecaption).val(selected_file_ids.length+" Selezionati");
                 }
                    $('#attach-databank-file-$attribute').modal('hide');
              }
          }
        });
     });
    }
JS;

$this->registerJs($js);

$js2 = <<<JS
    //PAGINAZIONE
  $(document).on('click', '#file-databank-file-$attribute .pagination a', function(e){
        e.preventDefault();
        let file_ids = selected_file_ids.join();
        let dataPage = parseInt($(this).attr('data-page')) +1;
         $.ajax({
          method: 'get',
          url: "/attachments/attach-databank-file/search-databank-file-ajax",
          data: $('#form-databank-file-$attribute').serialize()+'&attribute=$attribute&file_ids='+file_ids+'&page='+dataPage+'&per-page='+pageSize,
          success: function(data){
                $('#file-databank-file-$attribute').html(data);
                }
        });
  });

// RICERCA
$(document).on('click', '#btn-search-databank-file-$attribute', function(e){
        e.preventDefault();
        let file_ids = selected_file_ids.join();

         $.ajax({
          method: 'get',
          url: "/attachments/attach-databank-file/search-databank-file-ajax",
          data: $('#form-databank-file-$attribute').serialize()+'&attribute=$attribute&file_ids='+file_ids,
          success: function(data){
                $('#file-databank-file-$attribute').html(data);
                }
        }); 
    });

//CANCELLA RICERCA
 $(document).on('click', '#btn-cancel-databank-file-$attribute', function(e){
        e.preventDefault();
        let file_ids = selected_file_ids.join();
        var inputs = $('.content-search-databank-file').find('input[type="text"]');
        
        $(inputs).each(function(){
            $(this).val('');
        });
        $('.content-search-databank-file').find('select').each(function(){
            $(this).val('');
            $(this).trigger('change');
        });
        $('#custom-tags-search-id-$attribute').tagit("removeAll");
        
         $.ajax({
          method: 'get',
          url: "/attachments/attach-databank-file/search-databank-file-ajax",
          data: {attribute: '$attribute', file_ids : file_ids},
          success: function(data){
                $('#file-databank-file-$attribute').html(data);
                }
        }); 
    });
 
 //AUTOCOMPLETE TAGIT
 $(document).on('autocompleteresponse', '#form-databank-file-$attribute .tagit-new input', function(){
    $('.ui-menu.ui-widget-content.ui-autocomplete').each(function(){
        $(this).show();
    });
 });
JS;

$this->registerJs($js2);

$this->registerCss(".file-item-selected {
    border: solid 1px #349ce1 !important;
    background-color: #e6f5ff;
    border-radius: 6px;
    .modal-footer: display:none
");
?>

<div id="container-databank-file-<?= $attribute ?>">
    <?php $modelSearch = new \open20\amos\attachments\models\search\AttachDatabankFileSearch(); ?>
    <?php echo $this->render('_search_databank_file_view', [
        'modelSearch' => $modelSearch,
        'attribute' => $attribute
    ]) ?>



    <div id="file-databank-file-<?= $attribute ?>" class="file-databank-list">
        <?php
        $dataProvider = $modelSearch->searchGenericFiles([]);
        $dataProvider->pagination->pageSize = $pageSize;
        $dataProvider->pagination->route = '#';

        echo $this->render('items_databank_files', [
            'attribute' => $attribute,
            'dataProvider' => $dataProvider
        ]) ?>
    </div>
</div>
<div class="modal-footer">
    <div class="row">
        <div class="col-md-6 text-left">
            <div class="dropdown">
                <button id="label-selezionati-<?= $attribute ?>" type="button" data-toggle="dropdown" class="btn btn-link" aria-haspopup="true" aria-expanded="false">
                    <?= FileModule::t('amosattachments', 'File selezionati (0)') ?>
                    <span class="caret"></span>
                </button>
                <ul id="selected-files-<?= $attribute ?>" class="dropdown-menu" aria-labelledby="dLabel" style="padding:6px 14px">
                </ul>
            </div>
        </div>
        <div class="col-md-6 text-right">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= FileModule::t('amosattachments', "Chiudi") ?></button>
            <?= \yii\helpers\Html::a(FileModule::t('amosattachments', "Inserisci"), '#', [
                'class' => 'btn btn-primary pull-right',
                'id' => 'insert-files-btn-' . $attribute,
                'title' => FileModule::t('amosattachments', "Inserisci file")
            ]) ?>
        </div>
    </div>
</div>


<div style="display:none" id="container-preview-file-<?= $attribute ?>">
</div>