amos-attachments
----------------

Extension for file uploading and attaching to the models

Demo
----
You can see the demo on the [krajee](http://plugins.krajee.com/file-input/demo) website

Installation
------------

1. The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
composer require open20/amos-attachments
```

or add

```
"open20/amos-attachments": ">=1.0"
```

to the require section of your `composer.json` file.

2.  Add module to your main config in common:
	
```php
<?php
'aliases' => [
    '@file' => dirname(__DIR__),
],
'modules' => [
    'attachments' => [
        'class' => 'open20\amos\attachments\FileModule',
        'webDir' => 'files',
        'tempPath' => '@common/uploads/temp',
        'storePath' => '@common/uploads/store',
        // 'tableName' => '{{%attach_file}}' // Optional, default to 'attach_file'
    ],
],
```

3. Apply migrations

```bash
php yii migrate/up --migrationPath=@vendor/open20/amos-attachments/src/migrations
```

4. Attach behavior to your model (be sure that your model has "id" property)
	
```php
<?php
use yii\helpers\ArrayHelper;

/**
 * Adding the file behavior
 */
public function behaviors()
{
    return ArrayHelper::merge(parent::behaviors(), [
        'fileBehavior' => [
            'class' => \file\behaviors\FileBehavior::className()
        ]
    ]);
}

/**
 * Add the new fields to the file behavior
 */
public function rules()
{
    return ArrayHelper::merge(parent::rules(), [
        [['my_field_multiple_files', 'my_field_single_file'], 'file'],
    ]);
}
```
	
5. Make sure that you have added `'enctype' => 'multipart/form-data'` to the ActiveForm options
	
6. Make sure that you specified `maxFiles` in module rules and `maxFileCount` on `AttachmentsInput` to the number that you want

7. Youre ready to use, [See How](docs/index.md)
