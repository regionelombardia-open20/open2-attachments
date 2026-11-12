<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\attachments\models\base
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
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
 * @property string $shutterstock_image_id
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
    public $tagsImage = [];
    public $altDescription;

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
            [['shutterstock_image_id','aspect_ratio', 'tagsImage','customTags','created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name','altDescription'], 'string', 'max' => 255],
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
