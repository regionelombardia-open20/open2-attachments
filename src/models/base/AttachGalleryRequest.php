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
