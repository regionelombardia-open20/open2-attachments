Basic Usage
-----------

In the form.php of your model add file input

```php
<?= $form->field($model, 'myFieldMultipleFiles')->widget(\file\components\AttachmentsInput::classname(), [
    'id' => 'file-input', // Optional
    'model' => $model,
    'options' => [ // Options of the Kartik's FileInput widget
        'multiple' => true, // If you want to allow multiple upload, default to false
    ],
    'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget 
        'maxFileCount' => 10 // Client max files
    ]
]) ?>
```

Use widget to show all attachments of the model in the view.php

```php
<?= \file\components\AttachmentsTable::widget(['model' => $model]) ?>
```

(Deprecated) Add onclick action to your submit button that uploads all files before submitting form

```php
<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', [
    'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
    'onclick' => "$('#file-input').fileinput('upload');"
]) ?>
```

You can get all attached files by calling $model->files, for example:

```php
<?php
foreach ($model->files as $file) {
    echo $file->path;
}
```

Advanced Usage
--------------

### Custom Getters by Attribute

`From version 1.2.4`

You can replace public properties with custom getters to easily get images related to your attributes

```php
<?php
/**
 * @var $myFieldMultipleFiles \file\models\File[]
 * @var $myFieldSingleFile \file\models\File
 */

/**
 * Getter for $this->myFieldMultipleFiles;
 * @return \yii\db\ActiveQuery
 */
public function getMyFieldMultipleFiles() {
    return $this->hasMultipleFiles('myFieldMultipleFiles');
}

/**
 * Getter for $this->myFieldSingleFile;
 * @return \yii\db\ActiveQuery
 */
public function getMyFieldSingleFile() {
    return $this->hasOneFile('myFieldSingleFile');
}
?>
```

### Allow only images / Custom Validators

`From version 1.2.3`

It is possible to validate the files with any validator like image validator you only need to add the right rules to the file fields, eg ad in your model:

```php
<?php
/**
 * Implement Image validation
 */
public function rules()
{
    return ArrayHelper::merge(parent::rules(), [
        [['myFieldMultipleFiles', 'myFieldSingleFile'], 'file'],
        [['myFieldMultipleFiles', 'myFieldSingleFile'], 'image'],
    ]);
}
```

Cropper
-------

Thanks to [Yurknix](https://github.com/yurkinx/yii2-image) library for image manipulation in yii2, this plugin includes a cropper.

### Basic Cropping

`From version 1.2.5`

There is 3 base crop sizes

 * Small `square_small` - 100x100 px
 * Medium `square_medium` - 500x500 px
 * Large `square_large` - 1000x1000 px

To use the cropper you only need to pass the crop alias to the Url Getter of the File Record, eg:

```php
<?php
$cropUrl = $myRecordWithAttachments->myFieldSingleFile->getUrl('square_small');

echo $cropUrl; // http://sample.com/file/file/download?id=$this->id&hash=$this->hash&size=square_small
```

### Custom Crops

`From version 1.2.5`

You can define your own crops in the module configuration, for example:

```php
<?php
'modules' => [
    'file' => [
        'class' => 'file\FileModule',
        'webDir' => 'files',
        'tempPath' => '@common/uploads/temp',
        'storePath' => '@common/uploads/store',
        'tableName' => '{{%attach_file}}',
        'config' => [
            'crops' => [
                'square_small' => ['width' => 100, 'height' => 100, 'quality' => 100],
                'simone' => ['width' => 10000, 'height' => 1000, 'rotate_degrees' => 90]
            ]
        ]
    ],
]
```

Here is the list of cropping attributes you can set

 * `width`
 * `height`
 * `master`
 * `crop_width`
 * `crop_height`
 * `crop_width`
 * `crop_height`
 * `crop_offset_x`
 * `crop_offset_y`
 * `rotate_degrees`
 * `rotate_degrees`
 * `refrect_height`
 * `refrect_opacity`
 * `refrect_fade_in`
 * `flip_direction`
 * `flip_direction`
 * `bg_color`
 * `bg_opacity`
 * `quality`
 
 ### Upload from gallery
##### disableGallery  (default :true)
Turn off the gallery in all the platform
```php
<?php
'modules' => [
    'file' => [
       'disableGallery' => true
    ],
]
```
##### enableSingleGallery 
You can only have one gallery