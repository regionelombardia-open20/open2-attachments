<?php

namespace open20\amos\attachments\models\base;

use Yii;

/**
* This is the base-model class for table "admin_storage_image".
*
    * @property integer $id
    * @property integer $file_id
    * @property integer $filter_id
    * @property integer $resolution_width
    * @property integer $resolution_height
*/
 class  AdminStorageImage extends \open20\amos\core\record\Record
{
    public $isSearch = false;

/**
* @inheritdoc
*/
public static function tableName()
{
return 'admin_storage_image';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['file_id', 'filter_id', 'resolution_width', 'resolution_height'], 'integer'],
];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => Yii::t('app', 'ID'),
    'file_id' => Yii::t('app', 'File ID'),
    'filter_id' => Yii::t('app', 'Filter ID'),
    'resolution_width' => Yii::t('app', 'Resolution Width'),
    'resolution_height' => Yii::t('app', 'Resolution Height'),
];
}
}
