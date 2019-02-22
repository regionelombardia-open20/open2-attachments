<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\attachments\components
 * @category   CategoryName
 */

namespace lispa\amos\attachments\components;

use kartik\widgets\FileInput;
use lispa\amos\attachments\FileModule;
use lispa\amos\attachments\FileModuleTrait;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\jui\JuiAsset;

/**
 * Class AvatarInput
 * @property FileActiveRecord $model
 * @package lispa\amos\attachments\components
 */
class AvatarInput extends FileInput
{
    use FileModuleTrait;

    public $attribute;

    /** @var FileActiveRecord $model */
    public $model;

    public $pluginOptions = [];

    public $options = [];

    public function init()
    {
        JuiAsset::register($this->view);

        if (empty($this->model)) {
            throw new InvalidConfigException(FileModule::t('amosattachments', "Property {model} cannot be blank"));
        }

        FileHelper::removeDirectory($this->getModule()->getUserDirPath($this->attribute)); // Delete all uploaded files in past

        $initials = $this->model->isNewRecord ? [] : $this->model->getInitialPreviewByAttributeName($this->attribute);
        $initialCount = count($initials);

        $this->pluginOptions = array_replace(
            $this->pluginOptions,
            [
                //'uploadUrl' => Url::toRoute(['/file/file/upload', 'attribute' => $this->attribute, 'model' => get_class($this->model)]),
                'initialPreview' => $initials,
                'initialPreviewConfig' => $this->model->isNewRecord ? [] : $this->model->getInitialPreviewConfigByAttributeName($this->attribute),
                'uploadAsync' => false,
                //'otherActionButtons' => '<button class="download-file btn-xs btn-default" title="download" {dataKey}><i class="glyphicon glyphicon-download"></i></button>',
                'initialPreviewCount' => $initialCount,
                'validateInitialCount' => true,
                //'maxFileCount' => 5,
                'showPreview' => true,
                'showRemove' => false,
                'showUpload' => false,


                'overwriteInitial' => true,
                'showClose' => false,
                'showCaption' => false,
                'browseLabel' => '',
                'removeLabel' => '',
                'browseIcon' => '<i class="glyphicon glyphicon-folder-open"></i>',
                'removeIcon' => '<i class="glyphicon glyphicon-remove"></i>',
                'removeTitle' => 'Cancel or reset changes',
                'elErrorContainer' => '#kv-avatar-errors-1',
                'msgErrorClass' => 'alert alert-block alert-danger',
                'defaultPreviewContent' => '<img src="/uploads/default_avatar_male.jpg" alt="' . FileModule::t('amosattachments', 'Your Avatar') . '" style="width:160px">',
                'layoutTemplates' => ['main2' => '{preview} {remove} {browse}'],
                'allowedFileExtensions' => ["jpg", "jpeg", "png", "gif"]
            ]
        );

        $this->options = array_replace(
            $this->options,
            [
                //'id' => $this->id,
                'model' => $this->model,
                'attribute' => $this->attribute,
                'name' => $this->attribute . '[]',
                'multiple' => true
            ]
        );

        parent::init();

        $inputId = $this->options['id'];

        $urlSetMain = Url::toRoute('/' . FileModule::getModuleName() . '/file/set-main');
        $urlRenameFile = Url::toRoute('/' . FileModule::getModuleName() . '/file/rename');

        $js = <<<JS
            var fileInput{$this->attribute} = $('#{$inputId}');
            var files = fileInput{$this->attribute}.fileinput('getFilesCount');
            var form{$this->attribute} = fileInput{$this->attribute}.closest('form');
            
            form{$this->attribute}.on('beforeValidate', function(event) {
                if (files) {
                    form{$this->attribute}.yiiActiveForm('remove', '{$inputId}');
                }
            });
JS;

        \Yii::$app->view->registerJs($js);
    }
}
