<?php

namespace open20\amos\attachments\models\base;

use Yii;

/**
 * This is the base-model class for table "admin_storage_file".
 *
 * @property integer $id
 * @property integer $is_hidden
 * @property integer $folder_id
 * @property string $name_original
 * @property string $name_new
 * @property string $name_new_compound
 * @property string $mime_type
 * @property string $extension
 * @property string $hash_file
 * @property string $hash_name
 * @property integer $upload_timestamp
 * @property integer $file_size
 * @property integer $upload_user_id
 * @property integer $is_deleted
 * @property integer $passthrough_file
 * @property string $passthrough_file_password
 * @property integer $passthrough_file_stats
 * @property string $caption
 * @property integer $inline_disposition
 */
class  AdminStorageFile extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_storage_file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_hidden', 'folder_id', 'upload_timestamp', 'file_size', 'upload_user_id', 'is_deleted', 'passthrough_file', 'passthrough_file_stats', 'inline_disposition'], 'integer'],
            [['caption'], 'string'],
            [['name_original', 'name_new', 'name_new_compound', 'mime_type', 'extension', 'hash_file', 'hash_name'], 'string', 'max' => 255],
            [['passthrough_file_password'], 'string', 'max' => 40],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosattachments', 'ID'),
            'is_hidden' => Yii::t('amosattachments', 'Is Hidden'),
            'folder_id' => Yii::t('amosattachments', 'Folder ID'),
            'name_original' => Yii::t('amosattachments', 'Name Original'),
            'name_new' => Yii::t('amosattachments', 'Name New'),
            'name_new_compound' => Yii::t('amosattachments', 'Name New Compound'),
            'mime_type' => Yii::t('amosattachments', 'Mime Type'),
            'extension' => Yii::t('amosattachments', 'Extension'),
            'hash_file' => Yii::t('amosattachments', 'Hash File'),
            'hash_name' => Yii::t('amosattachments', 'Hash Name'),
            'upload_timestamp' => Yii::t('amosattachments', 'Upload Timestamp'),
            'file_size' => Yii::t('amosattachments', 'File Size'),
            'upload_user_id' => Yii::t('amosattachments', 'Upload User ID'),
            'is_deleted' => Yii::t('amosattachments', 'Is Deleted'),
            'passthrough_file' => Yii::t('amosattachments', 'Passthrough File'),
            'passthrough_file_password' => Yii::t('amosattachments', 'Passthrough File Password'),
            'passthrough_file_stats' => Yii::t('amosattachments', 'Passthrough File Stats'),
            'caption' => Yii::t('amosattachments', 'Caption'),
            'inline_disposition' => Yii::t('amosattachments', 'Inline Disposition'),
        ];
    }
}
