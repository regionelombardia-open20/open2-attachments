<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\attachments\models
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

namespace open20\amos\attachments\models;

use open20\amos\attachments\FileModule;
use open20\amos\attachments\FileModuleTrait;
use open20\amos\attachments\helpers\AttachemntsHelper;
use open20\amos\attachments\i18n\grammar\FileGrammar;
use open20\amos\attachments\models\AttachFileCategorie;
use open20\amos\attachments\utility\AttachmentsUtility;
use open20\amos\core\record\Record;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\helpers\VarDumper;

/**
 * Class File
 *
 * This is the model class for table "file".
 *
 * @property integer $id
 * @property string $name
 * @property string $model
 * @property string $attribute
 * @property integer $item_id
 * @property string $hash
 * @property integer $size
 * @property string $type
 * @property string $mime
 * @property integer $is_main
 * @property integer $original_attach_file_id
 * @property integer $date_upload
 * @property integer $sort
 * @property integer $encrypted
 * @property integer $num_downloads
 * @property string $table_name_form
 * @property integer $attach_file_categorie_id
 * @property integer $created_by
 *
 * @property FileRefs[] $attachFileRefs
 * @property \open20\amos\attachments\models\File[] $attachmentWithBrothers
 * @property AttachFileCategorie $categoria
 *
 * @package open20\amos\attachments\models
 */
class File extends Record
{
    /**
     *
     */
    use FileModuleTrait;

    /**
     *
     */
    const MAIN = 1;

    /**
     *
     */
    const NOT_MAIN = 0;

    /**
     * Const to encrypted files
     */
    const IS_ENCRYPTED = 1;

    /**
     *
     */
    const IS_NOT_ENCRYPTED = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return \Yii::$app->getModule(FileModule::getModuleName())->tableName;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
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
            [
                [
                    'original_attach_file_id', 'item_id', 'size', 'is_main',
                    'date_upload', 'sort', 'num_downloads',
                    'attach_file_categorie_id', 'created_by'
                ], 'integer'
            ],
            [['name', 'model', 'hash', 'type', 'mime', 'table_name_form'],
                'string', 'max' => 255],
            [['attach_file_categorie_id'], 'exist', 'skipOnError' => true,
                'targetClass' => AttachFileCategorie::className(),
                'targetAttribute' => ['attach_file_categorie_id' => 'id']],
        ];
    }

    /**
     * Back Compat
     * @return mixed|null
     */
    public function getitem_id()
    {
        return $this->item_id;
    }

    /**
     * Back Compat
     * @param $id
     * @return mixed
     */
    public function setitem_id($id)
    {
        return $this->item_id = $id;
    }

    /**
     * Back Compat
     * @return mixed|null
     */
    public function getItemId()
    {
        return $this->item_id;
    }

    /**
     * Back Compat
     * @param $id
     * @return mixed
     */
    public function setItemId($id)
    {
        return $this->item_id = $id;
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
            'item_id' => FileModule::t('amosattachments', 'Item ID'),
            'hash' => FileModule::t('amosattachments', 'Hash'),
            'size' => FileModule::t('amosattachments', 'Size'),
            'type' => FileModule::t('amosattachments', 'Type'),
            'mime' => FileModule::t('amosattachments', 'Mime'),
            'is_main' => FileModule::t('amosattachments', 'Is main'),
            'original_attach_file_id' => FileModule::t('amosattachments',
                'Original Attach File Id'),
            'date_upload' => FileModule::t('amosattachments', 'Date upload'),
            'sort' => FileModule::t('amosattachments', 'Sort'),
            'encrypted' => FileModule::t('amosattachments', '#encrypted'),
            'num_downloads' => FileModule::t('amosattachments', '#num_downloads'),
            'table_name_form' => FileModule::t('amosattachments',
                'Table Name Form'),
            'attach_file_categorie_id' => FileModule::t('amosattachments',
                'Categoria'),
            'created_by' => FileModule::t('amosattachments', 'Created By'),          
            'alt_description' => FileModule::t('amosattachments', '#alt_description'),
        ];
    }

    /**
     * @param string $size
     * @return string
     */
    public function getUrl($size = 'original', $absolute = false, $canCache = false)
    {
        $ref = FileRefs::getRefByAttachFile($this, $size);
        return $ref ? $ref->getUrl($size, $absolute, $canCache) : null;
    }

    /**
     * @param $size
     * @return string
     */
    public function getWebUrl($size = 'original', $absolute = false, $canCache = false)
    {
        $ref = FileRefs::getRefByAttachFile($this, $size, false);
        return $ref ? $ref->getUrl($size, $absolute, $canCache) : null;
    }

    /**
     * @param $size
     * @return string
     */
    public function getWebPUrl($size = 'original', $absolute = false, $canCache = false)
    {
        $ref = FileRefs::getRefByAttachFile($this, $size, false, true);
        return $ref ? $ref->getUrl($size, $absolute, $canCache) : null;
    }

    /**
     * @return string
     */
    public function getPath($size = 'original', $isPrivateFile = true)
    {
        $filesDirPath = AttachemntsHelper::getPathByHash($this->hash, !$isPrivateFile);

        if ($size == 'original') {
            return $filesDirPath
                . DIRECTORY_SEPARATOR
                . $this->hash
                . '.'
                . $this->type;
        }

        $moduleConfig = Yii::$app->getModule('attachments')->config;
        $crops = $moduleConfig['crops'] ?: [];

        if (json_decode($size) != null) {
            $crops['custom'] = (array)json_decode($size);
            $size = 'custom';
        }

        if (array_key_exists($size, $crops)) {
            $cropSettings = $crops[$size];
        } else {
            $crops['custom'] = (array)json_decode('default');
            $size = 'custom';
            $cropSettings = $crops[$size];
        }

        return $filesDirPath
            . DIRECTORY_SEPARATOR
            . $this->hash
            . '.'
            . (!empty($cropSettings['width'])
                ? $cropSettings['width'] : ''
            )
            . '.'
            . (!empty($cropSettings['height'])
                ? $cropSettings['height'] : ''
            )
            . '.'
            . $this->type;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachFileRefs()
    {
        return $this->hasOne(FileRefs::class, ['attach_file_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachFileRefsMany()
    {
        return $this->hasMany(FileRefs::class, ['attach_file_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachFileCopy()
    {
        return $this->hasOne(File::class, ['original_attach_file_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachFileOriginal()
    {
        return $this->hasOne(File::class, [ 'id' => 'original_attach_file_id']);
    }

    /**
     * @return integer
     */
    public function getNumDownloads()
    {
        return $this->num_downloads;
    }

    /**
     * TODO da implementare. Creare tabella criptata in cui salvare la password
     * univoca oppure una random per ciascuna riga di attach_file.
     * @return string
     */
    public function getDecryptPassword()
    {
        return '5dd2d749acf49';
    }

    /**
     *
     * @return ActiveRecord|ActiveQuery|null
     */
    public function getOwner()
    {
        if (class_exists($this->model) && method_exists($this->model, 'find')) {
            return $this->hasOne($this->model, ['id' => 'item_id']);
        }

        return null;
    }

    /**
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getAttachmentWithBrothers()
    {
        return $this->hasMany(self::className(), ['model' => 'model'])
            ->andWhere(['item_id' => $this->item_id])
            ->andWhere(['attribute' => $this->attribute]);
    }

    /**
     * @return string
     */
    public function getFormattedSize()
    {
        $dimScale = ['B', 'Kb', 'Mb', 'Gb', 'Tb'];
        $sizeFile = intval($this->size);
        $power = $sizeFile > 0
            ? floor(log($sizeFile, 1024))
            : 0;

        return number_format(
                $sizeFile / pow(1024, $power),
                0,
                '.',
                ','
            )
            . ' '
            . $dimScale[$power];
    }

    /**
     * @return string
     */
    public function getAttachmentIcon($onlyIcon = false)
    {
        return AttachmentsUtility::getAttachmentIcon($this, $onlyIcon);
    }
    
    /**
     * @return mixed
     */
    public function getGrammar()
    {
        return new FileGrammar();
    }
    
    /**
     * @return string The model title field value
     */
    public function getTitle()
    {
        return $this->name;
    }
    
    /**
     * @inheritdoc
     */
    public function getShortDescription()
    {
        return $this->name;
    }
    
    /**
     * @return string The model description field value
     */
    public function getDescription($truncate)
    {
        return $this->name;
    }
    
    /**
     * @inheritdoc
     */
    public function getFullViewUrl()
    {
        $ref = FileRefs::getRefByAttachFile($this, 'original', false);
        return Url::toRoute(["/" . $this->getViewUrl(), "hash" => $ref->hash]);
    }

    /**
     * @return AttachDatabankFile|AttachGalleryImage|null
     */
    public function saveInBackendFromLuya($class){
        $module = \Yii::$app->getModule('attachments');
        if($module) {
            if ($class == AttachDatabankFile::className()) {
                $databankFile = new AttachDatabankFile();
                $databankFile->name = $this->name;
                $databankFile->extension = $this->type;
                $databankFile->save(false);
                $newFile = $module->attachFile($this->path, $databankFile, 'attachmentFile', false, false, false, $this->name);
                $this->original_attach_file_id = $newFile->id;
                $this->save(false);
                $newFile->date_upload = $this->date_upload;
                $newFile->save(false);
                return $databankFile;
        } else if ($class== AttachGalleryImage::className()) {
                $image = new AttachGalleryImage();
                $image->gallery_id = 1;
                $image->name = $this->name;
                list($width, $height, $type, $attr) = getimagesize($this->path);
                $image->aspect_ratio = round($width / $height, 1);
                $image->save(false);
                $newFile = $module->attachFile($this->path, $image, 'attachImage', false, false, false, $this->name);
                $this->original_attach_file_id = $newFile->id;
                $this->save(false);
                $newFile->date_upload = $this->date_upload;
                $newFile->save(false);
                return $image;

            }
        }
        return null;
    }


    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function getAdminStorageFile(){
        $copy = $this->attachFileCopy;
        if($copy) {
            $storage = AdminStorageFile::find()
                ->andWhere(['name_new_compound' => $copy->name . '.' . $copy->type])
                ->one();
        }
        return $storage;

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(AttachFileCategorie::class,
            ['id' => 'attach_file_categorie_id']);
    }
    
    /**
     * Retrieves the name of the category associated with the attachment
     * @return string Name of the category associated with the attachment
     */
    public function getNomeCategoria()
    {
        $modelCat = $this->categoria;
        if (empty($modelCat)) {
            return FileModule::t('amosattachments', 'Nessuna categoria');
        }
        return $modelCat->titolo;
    }
    
    /**
     * Check if the attachment has valid category
     * @param boolean $emptyIsValid If true, the absence of a category
     * associated with the attachment will be considered as if the latter had
     * associated a valid category; if false, the lack of a category associated
     * with the attachment will be considered as an association with an invalid
     * category
     * @return bool Return true if the attachment has valid category or false
     * otherwise
     */
    public function hasValidCategoria($emptyIsValid = false)
    {
        if (empty($this->model)) {
            return false;
        }
        $modelCat = $this->categoria;
        if (empty($modelCat)) {
            if (!empty($emptyIsValid)) {
                return true;
            }
            return false;
        }
        return $this->categoria->isValidForModel($this->model);
    }
}
