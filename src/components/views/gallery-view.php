<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments
 * @category   CategoryName
 *
 * @var \yii\web\View $this
 * @var \open20\amos\attachments\components\GalleryInput $widget
 * @var \open20\amos\attachments\models\AttachGallery $gallery
 * @var \open20\amos\attachments\models\AttachGalleryImage [] $images
 * @var \open20\amos\attachments\models\AttachGalleryCategory [] $categories
 */

use open20\amos\attachments\FileModule;
if(preg_match('/^[a-zA-Z]+$/', $attribute) == 0){
    $attribute = '';
}


$js = <<<JS
    // Add the preview of the image and set in session the id of the file to attach
    $(document).on('click','.link-image' , function(e){
        e.preventDefault();
        var csrf = $('form input[name="_csrf-backend"]').val();
        var id_image = $(this).attr('data-key');
        var attribute = $(this).attr('data-attribute');
        var src_image = $(this).find('img').attr('src');
        var tag_img = "<img class='img-responsive' src='"+src_image+"' style='display:block;width:100%;height:auto; 'min-width:0!important;min-height:0!important; max-width:none!important;max-height:none!important; image-orientation:0deg!important;'>";
        
        $.ajax({
          method: 'get',
          url: "/attachments/attach-gallery-image/upload-from-gallery-ajax",
          data: {id: id_image, attribute: attribute,  csrf: csrf},
          success: function(data){
              if(data.success == true){
                $('.preview-pane .hidden').removeClass('hidden');
                $('.preview-container').html(tag_img);
                $('#attach-gallery-$attribute').modal('hide');
                $('.uploadcrop.attachment-uploadcrop').addClass('cropper-done');
                }
          }
        }); 
    });

    // delete the id of the file from session
    function deleteFromSession(){
         var csrf = $('form input[name="_csrf-backend"]').val();
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

JS;

$this->registerJs($js);

foreach ($categories as $category) {
    $images = $gallery->getAttachGalleryImages()->andWhere(['category_id' => $category->id])->all(); ?>
    <div class="col-xs-12 title"><h3><?= $category->name ?></h3></div>
    <div class="col-xs-12 images">
        <?php foreach ($images as $image) { ?>
            <div class="col-xs-4">
                <?php echo \yii\helpers\Html::a(
                    \yii\helpers\Html::img(!empty($image->attachImage) ? $image->attachImage->getUrl() : '', ['class' => 'img-responsive']),
                    '', ['id' => 'img-' . $image->id, 'class' => 'link-image', 'title' => FileModule::t('amosattachments', '#select_gallery_image'),
                    'data' => [
                        'key' => $image->id,
                        'attribute' => $attribute
                    ]
                ]) ?>
            </div>
        <?php } ?>
    </div>
<?php } ?>