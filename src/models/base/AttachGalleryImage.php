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

use Yii;

/**
 * This is the base-model class for table "attach_gallery_image".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $gallery_id
 * @property string $name
 * @property string $description
 * @property string $aspect_ratio
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\attachments\models\AttachGallery $gallery
 * @property \open20\amos\attachments\models\AttachGalleryCategory $category
 */
class  AttachGalleryImage extends \open20\amos\core\record\Record
{
    /**
     * 
     * @var type
     */
    public $customTags;
    
    /**
     * 
     * @var type
     */
    public $tagsImage;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attach_gallery_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gallery_id','name'], 'required'],
            [['category_id', 'gallery_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['description', 'customTags'], 'string'],
            [['aspect_ratio', 'tagsImage','customTags','created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['gallery_id'], 'exist', 'skipOnError' => true, 'targetClass' => AttachGallery::class, 'targetAttribute' => ['gallery_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosattachments', 'ID'),
            'category_id' => Yii::t('amosattachments', 'Category'),
            'gallery_id' => Yii::t('amosattachments', 'Gallery'),
            'name' => Yii::t('amosattachments', 'Name'),
            'description' => Yii::t('amosattachments', 'Description'),
            'created_at' => Yii::t('amosattachments', 'Created at'),
            'updated_at' => Yii::t('amosattachments', 'Updated at'),
            'deleted_at' => Yii::t('amosattachments', 'Deleted at'),
            'created_by' => Yii::t('amosattachments', 'Created by'),
            'updated_by' => Yii::t('amosattachments', 'Updated at'),
            'deleted_by' => Yii::t('amosattachments', 'Deleted at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGallery()
    {
        return $this->hasOne(
            \open20\amos\attachments\models\AttachGallery::class,
            ['id' => 'gallery_id']
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(
            \open20\amos\attachments\models\AttachGalleryCategory::class,
            ['id' => 'category_id']
        );
    }
}
