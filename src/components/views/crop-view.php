<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\attachments
 * @category   CategoryName
 *
 * @var \yii\web\View $this
 * @var \lispa\amos\attachments\components\CropInput $crop
 */

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Json;
use lispa\amos\attachments\FileModule;
use lispa\amos\attachments\assets\ModuleAttachmentsAsset;
use uitrick\yii2\widget\upload\crop\UploadCropAsset;
use lispa\amos\core\icons\AmosIcons;

$inputId = Html::getInputId($crop->model, $crop->attribute);
$moduleName = FileModule::getModuleName();

$js = <<<JS
//On delete button click
jQuery('.deleteImageCrop', '#cropInput_{$crop->attribute}').on('click', function() {
    //Metadata
    var data = jQuery(this).data();
    
    //Hide the button
    jQuery(this).addClass('hidden');
    
    //Remove the image
    jQuery('.preview-container img', '#cropInput_{$crop->attribute}').remove();
    
    //Clear crop if exists
    jQuery('.cropper-data', '#cropInput_{$crop->attribute}').attr('val', '');
    
    jQuery.get('/{$moduleName}/file/delete',{
        'id': data.id,
        'item_id': data.item_id,
        'model': data.model,
        'attribute': data.attribute
    }, function(result) {
        //TODO
    }, 'json');
});

//On new image selected
jQuery('.modal-footer button[class*="cropper-done"]', '#cropInput_{$crop->attribute}').on('click', function() {
    jQuery('.deleteImageCrop', '#cropInput_{$crop->attribute}').removeClass('hidden');
});
JS;

$this->registerJs($js, \yii\web\View::POS_READY);

ModuleAttachmentsAsset::register($this);

$attachament = $crop->model->{$crop->attribute};

?>
    <div class="uploadcrop attachment-uploadcrop" id="cropInput_<?= $crop->attribute; ?>">
        <?= $crop->form->field($crop->model, $crop->attribute)->fileInput()->label(FileModule::t('amosattachments', '#attach_label'), ['title' => FileModule::t('amosattachments', '#attach_label_title')]); ?>
        <?= Html::hiddenInput($crop->attribute . '_data', '', ['class' => 'cropper-data']); ?>

        <div class="preview-pane <?= (!is_null($crop->defaultPreviewImage)) ? 'image-find' : '' ?>">
            <?php $closeButtonClass = is_null($crop->defaultPreviewImage) ? ' hidden' : '';
            echo Html::a(AmosIcons::show('close', ['class' => 'btn btn-icon']), 'javascript:void(0)', [
                'class' => 'deleteImageCrop ' . $closeButtonClass,
                'title' => FileModule::t('amosattachments', '#attach_delete_image_crop'),
                'data' => [
                    'id' => $attachament ? $attachament->id : null,
                    'item_id' => $crop->model->id,
                    'model' => get_class($crop->model),
                    'attribute' => $crop->attribute
                ]
            ]);
            ?>
            <div class="preview-container">
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

        <?php Modal::begin([
            'id' => 'cropper-modal-' . $crop->imageOptions['id'],
            'header' => '<h2>' . FileModule::t('amosattachments', '#crop_title') . '</h2>',
            'closeButton' => [],
            'footer' => '<div class="row cropper-btns">'
                . Html::button(FileModule::t('amosattachments', '#cancel_btn'), ['id' => $crop->imageOptions['id'] . '_button_cancel', 'class' => 'btn btn-secondary', 'data-dismiss' => 'modal'])
                . Html::button(FileModule::t('amosattachments', '#accept_btn'), ['id' => $crop->imageOptions['id'] . '_button_accept', 'class' => 'btn btn-navigation-primary cropper-done']) . '</div>',
            'size' => Modal::SIZE_LARGE,
            'clientOptions' => ['backdrop' => 'static'] //To prevent closing when you drag outside the modal window
        ]); ?>
        <div id="image-source<?= $crop->imageOptions['id'] ?>" class="row cropper-body">
            <!-- Image crop area -->
            <div class="col-md-9">
                <div class="cropper-wrapper"></div>
            </div>
            <!-- preview column -->
            <div class="col-md-3">
                <div class="cropper-preview preview-lg"></div>
            </div>
        </div>
        <?php Modal::end(); ?>
    </div>

<?php // SELECTION FROM GALLERY
if ($crop->enableUploadFromGallery) {
    echo \lispa\amos\attachments\components\GalleryInput::widget(['attribute' => $crop->attribute]);
}
?>

<?php
UploadCropAsset::register($this);

$jcropOptions = ['inputField' => $inputField, 'jcropOptions' => $crop->jcropOptions];

$jcropOptions['maxSize'] = $crop->maxSize;

$jcropOptions = Json::encode($jcropOptions);

$jsCropper = <<<JS
    var options = {$jcropOptions};
    
    jQuery("#{$inputId}").uploadCrop(options);
JS;


$this->registerJs($jsCropper);
?>