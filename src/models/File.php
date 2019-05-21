<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\attachments
 * @category   CategoryName
 */

namespace lispa\amos\attachments\models;

use lispa\amos\attachments\FileModule;
use lispa\amos\attachments\FileModuleTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * This is the model class for table "file".
 *
 * @property integer $id
 * @property string $name
 * @property string $model
 * @property string $attribute
 * @property integer $itemId
 * @property string $hash
 * @property integer $size
 * @property string $type
 * @property string $mime
 * @property integer $is_main
 * @property integer $date_upload
 * @property integer $sort
 * @property FileRefs[] $attachFileRefs
 */
class File extends ActiveRecord
{
    use FileModuleTrait;

    const MAIN = 1;
    const NOT_MAIN = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return \Yii::$app->getModule(FileModule::getModuleName())->tableName;
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'date_upload',
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model', 'attribute', 'hash', 'size', 'mime'], 'required'],
            [['itemId', 'size', 'is_main', 'date_upload', 'sort', 'num_downloads'], 'integer'],
            [['name', 'model', 'hash', 'type', 'mime'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FileModule::t('amosattachments', 'ID'),
            'name' => FileModule::t('amosattachments', 'Name'),
            'model' => FileModule::t('amosattachments', 'Model'),
            'attribute' => FileModule::t('amosattachments', 'Attribute'),
            'itemId' => FileModule::t('amosattachments', 'Item ID'),
            'hash' => FileModule::t('amosattachments', 'Hash'),
            'size' => FileModule::t('amosattachments', 'Size'),
            'type' => FileModule::t('amosattachments', 'Type'),
            'mime' => FileModule::t('amosattachments', 'Mime'),
            'is_main' => FileModule::t('amosattachments', 'Is main'),
            'date_upload' => FileModule::t('amosattachments', 'Date upload'),
            'sort' => FileModule::t('amosattachments', 'Sort'),
            'num_downloads' => FileModule::t('amosattachments', '#num_downloads'),
        ];
    }

    /**
     * @param string $size
     * @return string
     */
    public function getUrl($size = 'original', $absolute = false, $canCache = false)
    {
        $hash = FileRefs::getHashByAttachFile($this, $size);
        return $this->generateUrlForHash($hash, $absolute,$canCache);
    }

    /**
     * @param $size
     * @return string
     */
    public function getWebUrl($size = 'original', $absolute = false, $canCache = false)
    {
        $hash = FileRefs::getHashByAttachFile($this, $size, false);
        return $this->generateUrlForHash($hash, $absolute,$canCache);
    }

    /**
     * 
     * @param type $hash
     * @param type $absolute
     * @param type $canCache
     * @return type
     */
    public function generateUrlForHash($hash, $absolute, $canCache = false) {
        $baseUrl = Url::to(['/' . FileModule::getModuleName() . '/file/view', 'hash' => $hash, 'canCache' => $canCache]);

        if (!$absolute)
            return $baseUrl;
        else
            return \Yii::$app->getUrlManager()->createAbsoluteUrl($baseUrl);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->getModule()->getFilesDirPath($this->hash) . DIRECTORY_SEPARATOR . $this->hash . '.' . $this->type;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachFileRefs()
    {
        return $this->hasOne(FileRefs::className(), ['attach_file_id' => 'id']);
    }
    
    /**
     * 
     * @return integer
     */
    public function getNumDownloads(){
        return $this->num_downloads;
    }
}
