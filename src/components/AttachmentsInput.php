<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\components
 * @category   CategoryName
 */

namespace open20\amos\attachments\components;

use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\attachments\FileModule;
use open20\amos\attachments\FileModuleTrait;

use kartik\widgets\FileInput;

use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\jui\JuiAsset;

/**
 * Class AttachmentsInput
 * @package File\components
 * @property FileActiveRecord $model
 */
class AttachmentsInput extends FileInput
{
    use FileModuleTrait;

    /**
     * 
     * @var type
     */
    
    public $asyncMode = false;

    /**
     * 
     * @var type
     */
    public $enableGoogleDrive = false;

    /**
     * 
     * @var type
     */
    public $enableUploadFromGallery = false;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        JuiAsset::register($this->view);

        if (empty($this->model)) {
            throw new InvalidConfigException(
                FileModule::t('amosattachments', "Property {model} cannot be blank")
            );
        }

        // Delete all uploaded files in past
        FileHelper::removeDirectory($this->getModule()->getUserDirPath($this->attribute));

        $initials = $this->model->isNewRecord 
            ? [] 
            : $this->model->getInitialPreviewByAttributeName(
                $this->attribute, 
                ['width' => 326, 'height' => 348, 'quality' => 80]
            )
        ;
        
        $initialCount = count($initials);
        $driveButton = '';

        $fileValidatorForAttribute = $this->model->getFileValidator($this->attribute);

        //Async mode triggers the upload for model mode to multiselect files
        if ($this->asyncMode) {
            //Url to upload files
            $this->pluginOptions['uploadUrl'] = Url::toRoute([
                '/file/file/upload-for-record',
                'attribute' => $this->attribute,
                'model' => get_class($this->model),
                'id' => get_class($this->model->id)
            ]);

            //Async mode
            $this->pluginOptions['uploadAsync'] = false;
        }


        // GIMAGE GALLERY
        $fileModule = \Yii::$app->getModule('attachments');
        if($fileModule){
            if($fileModule->disableGallery){
                $this->enableUploadFromGallery = false;
            }
        }
        if ($this->enableUploadFromGallery) {
            $buttonGallery =  \open20\amos\attachments\components\GalleryInput::widget(['attribute' => $this->attribute]);
        }

        if($this->enableGoogleDrive) {
                $driveButton = Html::button(AmosIcons::show('google-drive'), ['id' => 'auth', 'class' => 'btn btn-primary']);
        }

        $this->pluginOptions = array_replace(
            [
                'initialPreview' => $initials,
                'initialPreviewConfig' => $this->model->isNewRecord ? [] : $this->model->getInitialPreviewConfigByAttributeName($this->attribute),
                //'otherActionButtons' => '<button class="download-file btn-xs btn-default" title="download" {dataKey}><i class="glyphicon glyphicon-download"></i></button>',
                'overwriteInitial' => false,
                'initialPreviewCount' => $initialCount,
                'validateInitialCount' => true,
                'maxFileCount' => $fileValidatorForAttribute ? $fileValidatorForAttribute->maxFiles : 1,
                'showPreview' => true,
                'showCaption' => true,
                'showRemove' => true,
                'dropZoneEnabled' => false,
                'showDrag' => false,
                'indicatorNew' => false,
                'previewSettings' => [
                    'image' => [
                        'width' => '100px',
                        'height' => '100px'
                    ]
                ],
                'allowedPreviewTypes' => false,
                'previewFileIconSettings' => [
                    'docx' => '<span class="glyphicon glyphicon-file"></span>',
                    'jpg' => '<span class="glyphicon glyphicon-file"></span>',
                    'png' => '<span class="glyphicon glyphicon-file"></span>',
                    'gif' => '<span class="glyphicon glyphicon-file"></span>',
                ],
                'showUpload' => false,
                'layoutTemplates' => [
                    'actions' => '<div class="file-actions"><div class="file-footer-buttons">{upload} {delete} {zoom} {other}</div><div class="clearfix"></div></div>'
                ],
                'minFileSize' => null
            ],
            $this->pluginOptions
        );

        if ($this->pluginOptions['showPreview'] == false) {
            $this->pluginOptions['elErrorContainer'] = '#errorDropUpload-' . $this->attribute;
            // OLD code to see that caption were converted in the 3 lines with file-caption classes.
//            $this->pluginOptions['layoutTemplates']['main1'] = "<div id=\"errorDropUpload-{$this->attribute}\"></div>
//                                                                {preview}
//                                                                <div class=\"kv-upload-progress kv-hidden\"></div><div class=\"clearfix\"></div>
//                                                                <div class=\"input-group {class}\">
//                                                                  {caption}
//                                                                  <div class=\"input-group-btn\">
//                                                                    {cancel}
//                                                                    {upload}
//                                                                    {browse}" . $driveButton . "
//                                                                  </div>
//                                                                </div>";
            $this->pluginOptions['layoutTemplates']['main1'] = "<div id=\"errorDropUpload-{$this->attribute}\"></div>
                                                                {preview}
                                                                <div class=\"kv-upload-progress kv-hidden\"></div><div class=\"clearfix\"></div>
                                                                <div class=\"input-group {class}\">
                                                                  <div class=\"file-caption form-control {class}\" tabindex=\"500\">
                                                                    <span class=\"file-caption-icon\"></span>
                                                                    <input class=\"file-caption-name\" onkeydown=\"return false;\" onpaste=\"return false;\" tabindex=\"-1\">
                                                                  </div>
                                                                  <div class=\"input-group-btn\">
                                                                    {cancel}
                                                                    {upload}
                                                                    {browse}" . $driveButton . "
                                                                  </div>
                                                                </div>";

            $this->pluginOptions['layoutTemplates']['main2'] = "<div id=\"errorDropUpload -{$this->attribute}\"></div>
                                                                {preview}
                                                                <div class=\"kv-upload-progress hide\"></div>
                                                                {remove}{cancel}\n{upload}\n{browse}" . $driveButton;
        } else {
            // OLD code to see that caption were converted in the 3 lines with file-caption classes.
//            $this->pluginOptions['layoutTemplates']['main1'] = '{preview}
//                <div class="kv-upload-progress kv-hidden"></div><div class="clearfix"></div>
//                <div class="input-group {class}">
//                  {caption}
//                <div class="input-group-btn input-group-append"> {remove}{cancel}{upload}{browse}'. $driveButton.'
//                 </div>
//                </div>';
            $this->pluginOptions['layoutTemplates']['main1'] = '{preview}
                <div class="kv-upload-progress kv-hidden"></div><div class="clearfix"></div>
                <div class="input-group {class}">
                <div class="file-caption form-control {class}" tabindex="500">
                    <span class="file-caption-icon"></span>
                    <input class="file-caption-name" onkeydown="return false;" onpaste="return false;" tabindex="-1">
                </div>
                <div class="input-group-btn input-group-append"> {remove}{cancel}{upload}{browse}'. $driveButton.'
                 </div>
                </div>'.$buttonGallery;
            $this->pluginOptions['layoutTemplates']['main2'] = '{preview}<div class="kv-upload-progress kv-hidden"></div><div class="clearfix"></div>
               {remove}{cancel}{upload}{browse}'. $driveButton.$buttonGallery;
        }

        $fileAttribute = $this->pluginOptions['maxFileCount'] != 1 ? '[]' : '';

        $this->options = array_replace(
            $this->options,
            [
                //'id' => $this->id,
                //'model' => $this->model,  COMMENTATO perchè si rompeva, essendo che viene passato il model come attributo tel tag input

                'attribute' => $this->attribute,
                'name' =>  (!empty($this->name) ? $this->name : \yii\helpers\Html::getInputName($this->model,  $this->attribute)) . $fileAttribute,
                'multiple' => (!empty($fileAttribute))
            ]
        );

        @parent::init();

        $inputId = $this->options['id'];

        $urlSetMain = Url::toRoute('/' . FileModule::getModuleName() . '/file/set-main');
        $confirmText = FileModule::t('amosattachments', "If you choose another file, the current file will be deleted");
        $maxFiles = $this->pluginOptions['maxFileCount'];
        if ($maxFiles == false) {
            $maxFiles = 0;
        }
        $js = <<<JS
            var fileInput{$this->attribute} = $('#{$inputId}');
            var files{$this->attribute} = fileInput{$this->attribute}.fileinput('getFilesCount');
            var form{$this->attribute} = fileInput{$this->attribute}.closest('form');
            
            form{$this->attribute}.on('beforeValidate', function(event) {
                if (files{$this->attribute}) {
                    form{$this->attribute}.yiiActiveForm('remove', '{$inputId}');
                }
            });
            fileInput{$this->attribute}.on('click', function(event) {
                var imputBox = jQuery(this).parents('.file-input');
                var thumbs = jQuery('.file-preview .file-preview-thumbnails .file-preview-frame',imputBox);
                        
                if({$maxFiles} == 1 && thumbs.length >= 1) {
                    var r = confirm("{$confirmText}");
                    
                    if(typeof filesStack{$this->attribute} !== 'undefined'){
                        console.log(filesStack{$this->attribute});
                    }
                    if (r == false) {
                        event.preventDefault();
                    } else {
                        thumbs.each(function() {
                            var deleteButton = jQuery('.file-footer-buttons .kv-file-remove');
                            
                            deleteButton.trigger('click');
                        });
                    }
                }
            });
JS;

        \Yii::$app->view->registerJs($js);
    }
}
