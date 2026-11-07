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

Other Functions
-----------

### Cropper
For some image formats tou can enable cropping/resizing/rotation ecc [Read the Doc](crop.md)
 
### Upload from gallery
##### disableGallery  (default :true)
Turn off the gallery in all the platform
```php
<?php
'modules' => [
    'attachments' => [
       'disableGallery' => true
    ],
]
```
##### enableSingleGallery 
You can only have one gallery

### Antivirus Scan

`From version 1.11.0`

Antivirus scanning for new files can be enabled in few steps, first you must set to TRUE the antivirus option
```php
<?php
'modules' => [
    'attachments' => [
       'enableVirusScan' => true
    ],
]
```

Next you can configure the scanner class, by default is `\open20\amos\attachments\scanners\ClamAVScanner`, eg.

```php
<?php
'modules' => [
    'attachments' => [
       'virusScanClass' => '\open20\amos\attachments\scanners\ClamAVScanner'
    ],
]
```
In this case, you must have the required software installed on the server, `apt install clamav-daemon`

### Statically served files

`From version 1.11.0`

It is possible to serve files without any application call (public files only), this reduces the application load when serving static files, like images, thumbnails ecc

You can do it by simply configure a new bootstrap component like this
```php
<?php
'bootstrap' => [
    'open20\amos\attachments\bootstrap\StaticFilesManagement'
]
```