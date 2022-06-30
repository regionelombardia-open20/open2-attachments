<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\models\base
 * @category   CategoryName
 */

namespace open20\amos\attachments\models\base;

use open20\amos\attachments\FileModule;
use Yii;

/**
 * This is the base-model class for table "attach_gallery_request".
 *
 * @property integer $id
 * @property string $title
 * @property string $status
 * @property string $aspect_ratio
 * @property string $aspect_ratio_reply
 * @property string $text_request
 * @property string $text_reply
 * @property integer $attach_gallery_id
 * @property integer $attach_gallery_image_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\attachments\models\AttachGallery $attachGallery
 * @property \open20\amos\attachments\models\AttachGalleryImage $attachGalleryImage
 */
class  AttachGalleryRequest extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attach_gallery_request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text_request', 'text_reply'], 'string'],
            [['title', 'aspect_ratio'],'required'],
            [['attach_gallery_id', 'attach_gallery_image_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title', 'status', 'aspect_ratio','aspect_ratio_reply'], 'string', 'max' => 255],
            [['attach_gallery_id'], 'exist', 'skipOnError' => true, 'targetClass' => AttachGallery::class, 'targetAttribute' => ['attach_gallery_id' => 'id']],
            [['attach_gallery_image_id'], 'exist', 'skipOnError' => true, 'targetClass' => AttachGalleryImage::class, 'targetAttribute' => ['attach_gallery_image_id' => 'id']],
            ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FileModule::t('amosattachments', 'ID'),
            'title' => FileModule::t('amosattachments', 'Title'),
            'status' => FileModule::t('amosattachmnets', 'Status'),
            'aspect_ratio' => FileModule::t('amosattachments', 'Aspect ratio'),
            'text_request' => FileModule::t('amosattachments', 'Text Request'),
            'text_reply' => FileModule::t('amosattachments', 'Text reply'),
            'attach_gallery_id' => FileModule::t('amosattachments', 'Gallery'),
            'attach_gallery_image_id' => FileModule::t('amosattachments', 'image'),
            'created_at' => FileModule::t('amosattachments', 'Created at'),
            'updated_at' => FileModule::t('amosattachments', 'Updated at'),
            'deleted_at' => FileModule::t('amosattachments', 'Deleted at'),
            'created_by' => FileModule::t('amosattachments', 'Created by'),
            'updated_by' => FileModule::t('amosattachments', 'Updated at'),
            'deleted_by' => FileModule::t('amosattachments', 'Deleted at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachGallery()
    {
        return $this->hasOne(
            \open20\amos\attachments\models\AttachGallery::class,
            ['id' => 'attach_gallery_id']
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachGalleryImage()
    {
        return $this->hasOne(
            \open20\amos\attachments\models\AttachGalleryImage::class,
            ['id' => 'attach_gallery_image_id']
        );
    }
}
