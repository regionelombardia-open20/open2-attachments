<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\models
 * @category   CategoryName
 */

namespace open20\amos\attachments\models;

use open20\amos\admin\models\UserProfile;
use open20\amos\attachments\behaviors\FileBehavior;
use open20\amos\attachments\FileModule;
use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\tag\models\Tag;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "attach_gallery_image".
 */
class AttachGalleryImage
    extends
    \open20\amos\attachments\models\base\AttachGalleryImage
{
    /**
     *
     */
    const ROOT_TAG_CUSTOM = 'root_tag_custom_attach';

    /**
     *
     */
    const DEFAULT_ASPECT_RATIO = '1.7';


    public $errorMessage;

    /**
     * Adding the file behavior
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'fileBehavior' => [
                    'class' => FileBehavior::class
                ]
            ]
        );
    }

    /**
     * inserire il campo o i campi rappresentativi del modulo
     * @return type
     */
    public function representingColumn()
    {
        return [];
    }

    public function attributeHints()
    {
        return [
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['attachImage'], 'file']
        ]);
    }

    /**
     *
     */
    public function validateFiles()
    {
        // se ho selezionato qualcosa da shatterstock allora non ho bisogn odi validare
        $isLoaded = \Yii::$app->request->post('shutterstock_file_is_selected_attachImage');
        if (!empty($isLoaded)) {
            return true;
        }

        foreach ((array)$_FILES['AttachGalleryImage']['name'] as $attribute => $filename) {
            if ($attribute == 'attachImage') {
                if (empty($filename)) {
                    if (empty($this->$attribute)) {
                        $this->addError($attribute, "E' necessario inserire un file.");
                        return false;
                    }
                }
            }
        }
        return true;
    }


    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                ]);
    }


    public function getEditFields()
    {
        $labels = $this->attributeLabels();

        return [
            [
                'slug' => 'category_id',
                'label' => $labels['category_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'gallery_id',
                'label' => $labels['gallery_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'name',
                'label' => $labels['name'],
                'type' => 'string'
            ],
            [
                'slug' => 'description',
                'label' => $labels['description'],
                'type' => 'text'
            ],
        ];
    }

    /**
     * @return string marker path
     */
    public function getIconMarker()
    {
        return null; //TODO
    }

    /**
     * If events are more than one, set 'array' => true in the calendarView in the index.
     * @return array events
     */
    public function getEvents()
    {
        return NULL; //TODO
    }

    /**
     * @return url event (calendar of activities)
     */
    public function getUrlEvent()
    {
        return NULL; //TODO e.g. Yii::$app->urlManager->createUrl([]);
    }

    /**
     * @return color event
     */
    public function getColorEvent()
    {
        return NULL; //TODO
    }

    /**
     * @return title event
     */
    public function getTitleEvent()
    {
        return NULL; //TODO
    }

    /**
     *
     */
    public function saveCustomTags()
    {
        $root = Tag::find()->andWhere(['codice' => self::ROOT_TAG_CUSTOM])->one();
        if ($root) {
            EntitysTagsMm::deleteAll(['root_id' => $root->id, 'record_id' => $this->id, 'classname' => AttachGalleryImage::class]);
            $exploded = explode(',', $this->customTags);
            foreach ($exploded as $tagString) {
                if (!empty($tagString)) {
                    $tag = Tag::find()->andWhere(['nome' => $tagString, 'root' => $root->id])->one();
                    if (empty($tag)) {
                        $tag = new Tag();
                        $tag->nome = $tagString;
                        $tag->appendTo($root);
                        $ok = $tag->save(false);
                    }
                    if (!empty($tag->id)) {
                        $tagsMm = new EntitysTagsMm();
                        $tagsMm->tag_id = $tag->id;
                        $tagsMm->record_id = $this->id;
                        $tagsMm->root_id = $root->id;
                        $tagsMm->classname = AttachGalleryImage::class;
                        $tagsMm->save(false);
                    }
                }
            }
        }
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function loadCustomTags()
    {
        $this->customTags = [];

        $root = Tag::find()->andWhere(['codice' => self::ROOT_TAG_CUSTOM])->one();
        if ($root) {
            $tagsMm = EntitysTagsMm::find()
                ->innerJoin('tag', 'tag.id = entitys_tags_mm.tag_id')
                ->andWhere(['classname' => AttachGalleryImage::class])
                ->andWhere(['record_id' => $this->id])
                ->andWhere(['root_id' => $root->id])
                ->andWhere(['IS', 'codice', null])
                ->all();
            foreach ($tagsMm as $tagMm) {
                $this->customTags [] = $tagMm->tag->nome;
            }
            $this->customTags = implode(',', $this->customTags);
        }
    }

    /**
     *
     */
    public function saveImageTags()
    {
        $module = \Yii::$app->getModule('attachments');
        $codice = $module->codiceTagGallery;
        if ($codice) {
            $root = Tag::find()->andWhere(['codice' => $codice])->one();
            if ($root) {
                EntitysTagsMm::deleteAll(['root_id' => $root->id, 'record_id' => $this->id, 'classname' => AttachGalleryImage::class]);
                foreach ($this->tagsImage as $tagId) {
                    $tagsMm = new EntitysTagsMm();
                    $tagsMm->tag_id = $tagId;
                    $tagsMm->record_id = $this->id;
                    $tagsMm->root_id = $root->id;
                    $tagsMm->classname = AttachGalleryImage::class;
                    $tagsMm->save(false);
                }
            }
        }
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function loadTagsImage()
    {
        $module = \Yii::$app->getModule('attachments');
        $codice = $module->codiceTagGallery;
        if ($codice) {
            $root = Tag::find()->andWhere(['codice' => $codice])->one();
            if ($root) {
                $tagsMm = EntitysTagsMm::find()
                    ->andWhere(['root_id' => $root->id])
                    ->andWhere(['record_id' => $this->id])
                    ->andWhere(['classname' => AttachGalleryImage::class])
                    ->all();
                foreach ($tagsMm as $tagMm) {
                    $this->tagsImage [] = $tagMm->tag_id;
                }
                $this->tagsImage = array_unique($this->tagsImage);
            }
        }
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getTagIntereseInformativo()
    {
        $module = \Yii::$app->getModule('attachments');
        $codice = $module->codiceTagGallery;
        $tags = [];
        if ($codice) {
            $root = Tag::find()
                ->andWhere(['codice' => $codice])->one();

            if ($root) {
                $tags = Tag::find()->andWhere(['root' => $root->id, 'lvl' => 1])
                    ->andWhere(['deleted_at' => null])
                    ->orderBy('nome ASC')->all();
            }
        }
        return $tags;
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getTagImagesString()
    {
        $tags = [];
        $this->loadTagsImage();
        foreach ($this->tagsImage as $tag_id) {
            $tag = Tag::findOne($tag_id);
            if ($tag) {
                $tags [] = $tag->nome;
            }
        }
        return implode(', ', $tags);
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getCustomTagsString()
    {
        $this->loadCustomTags();
        return $this->customTags;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function getTagsImageModel()
    {
        $tags = [];
        $this->loadTagsImage();
        foreach ($this->tagsImage as $tag_id) {
            $tag = Tag::findOne($tag_id);
            if ($tag) {
                $tags [] = $tag;
            }
        }
        return $tags;
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function getCustomTagsModel()
    {
        $tags = [];
        $this->loadCustomTags();
        $explode = explode(',', $this->customTags);
        foreach ($explode as $tag_name) {
            $tag = Tag::find()->andWhere(['nome' => $tag_name])->one();
            if ($tag) {
                if (!empty($tag->nome)) {
                    $tags [] = $tag;
                }
            }
        }
        return $tags;
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getCreatedByProfile()
    {
        $profile = UserProfile::find()->andWhere(['user_id' => $this->created_by])->one();
        if ($profile) {
            return $profile->nomeCognome;
        }
        return '-';
    }

    //save on luya only if the folder is configured
    public function saveOnLuya()
    {
        /** @var  $module FileModule */
        $module = \Yii::$app->getModule('attachments');
        if ($module) {
            $folder_id = $module->luyaGalleryFolderId;
            $file = $this->attachImage;
            if ($file && !empty($folder_id)) {
                list($width, $height, $type, $attr) = getimagesize($file->path);
                $empty = new EmptyContentModel();
                $newFile = $module->attachFile($file->path, $empty, 'file', false, true, false, $file->name, $file->id);
                $newFile->detachBehavior('SoftDeleteByBehavior');
                $hash = substr(md5(rand()), 0, 8);
                $newFile->name = $file->name . '_' . $hash;
                $newFile->attribute = 'file';
                $newFile->item_id = 1;
                $newFile->save(false);

                $attributes ['File'] = $newFile->attributes;
                $crop_0 = new File();
                $crop_0->detachBehavior('SoftDeleteByBehavior');
                $crop_0->load($attributes);
                $crop_0->name = '0_' . $newFile->name;
                $crop_0->save(false);


                $storage = new AdminStorageFile();
                $storage->detachBehavior('SoftDeleteByBehavior');
                $storage->detachBehavior('TimestampBehavior');
                $storage->detachBehavior('BlameableBehavior');
                $storage->is_hidden = 0;
                $storage->folder_id = $folder_id;
                $storage->name_original = $this->name;
                $storage->name_new = $newFile->name;
                $storage->name_new_compound = $newFile->name . '.' . $newFile->type;
                $storage->mime_type = $newFile->mime;
                $storage->extension = $newFile->type;
                $storage->hash_file = hash('md5', rand());
                $storage->hash_name = $hash;
                $storage->upload_timestamp = $newFile->date_upload;
                $storage->file_size = $newFile->size;
                $storage->upload_user_id = 1;
                $storage->save(false);

                $storageImage = new AdminStorageImage();
                $storageImage->detachBehavior('SoftDeleteByBehavior');
                $storageImage->detachBehavior('TimestampBehavior');
                $storageImage->detachBehavior('BlameableBehavior');

                $storageImage->file_id = $storage->id;
                $storageImage->filter_id = '0';
                $storageImage->resolution_width = $width;
                $storageImage->resolution_height = $height;
                $storageImage->save(false);
                if (isset(\Yii::$app->cache)) {
                    \Yii::$app->cache->flush();
                }
            }
        }

    }

//    public function afterSave($insert, $changedAttributes)
//    {
//        parent::afterSave($insert, $changedAttributes);
//        /** @var  $file File */
//        $file = $this->attachImage;
//        if ($file) {
//            $storage = $file->getAdminStorageFile();
//            $storage->name = $this->name;
//            $storage->saveCms();
//        }
//    }
}
