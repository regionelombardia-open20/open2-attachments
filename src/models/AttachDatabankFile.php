<?php

namespace open20\amos\attachments\models;

use open20\amos\admin\models\UserProfile;
use open20\amos\attachments\behaviors\FileBehavior;
use open20\amos\attachments\components\DatabankFileInput;
use open20\amos\attachments\FileModule;
use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\tag\models\Tag;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "attach_databank_file".
 */
class AttachDatabankFile extends \open20\amos\attachments\models\base\AttachDatabankFile
{

    const ROOT_TAG_DATABANK_FILE = 'root_tag_databank_file';

    public $customTags;
    public $fromLuya = false;


    public function representingColumn()
    {
        return [
//inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints()
    {
        return [
        ];
    }

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
            [['attachmentFile'], 'file']
        ]);
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                ]);
    }

    public static function getFilesLuya()
    {
        $luyaItemFile = \Yii::$app->storage->findFiles();
        $filesArray = [];
        foreach ($luyaItemFile as $itemFile) {
//            pr($itemFile);
            $model = new AttachDatabankFile();
            $model->id = $itemFile->itemArray['name'];

            $model->name = $itemFile->itemArray['name_new'];
            $model->fromLuya = true;
            $attachment = $model->getAttachmentModel();
//            pr($attachment->attributes);
            $filesArray[] = $model;
//            $filesArray['name'] = $b->itemArray['name_new_compound'];
//            $filesArray['type'] = $b->itemArray['extension'];
//            $filesArray['id'] = $b->itemArray['name_new_compound'];
//            pr($b->name_new_compound);
        }
        return $filesArray;
    }

    public function getEditFields()
    {
        $labels = $this->attributeLabels();

        return [
            [
                'slug' => 'filename',
                'label' => $labels['filename'],
                'type' => 'string'
            ],
            [
                'slug' => 'extension',
                'label' => $labels['extension'],
                'type' => 'string'
            ],
            [
                'slug' => 'uploaded_from',
                'label' => $labels['uploaded_from'],
                'type' => 'string'
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
        $root = Tag::find()->andWhere(['codice' => self::ROOT_TAG_DATABANK_FILE])->one();
        if ($root) {
            EntitysTagsMm::deleteAll(['root_id' => $root->id, 'record_id' => $this->id, 'classname' => AttachDatabankFile::class]);
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
                        $tagsMm->classname = AttachDatabankFile::class;
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

        $root = Tag::find()->andWhere(['codice' => self::ROOT_TAG_DATABANK_FILE])->one();
        if ($root) {
            $tagsMm = EntitysTagsMm::find()
                ->innerJoin('tag', 'tag.id = entitys_tags_mm.tag_id')
                ->andWhere(['classname' => AttachDatabankFile::class])
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
    public function validateFiles()
    {
        foreach ((array)$_FILES['AttachDatabankFile']['name'] as $attribute => $filename) {
            if ($attribute == 'attachmentFile') {
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
     * save on luya only if the folder is configured
     * @return void
     */
    public function saveOnLuya()
    {
        /** @var  $module FileModule*/
        $module = \Yii::$app->getModule('attachments');
        if ($module) {
            $folder_id = $module->luyaDatabankFileFolderId;
            $file = $this->attachmentFile;
            if ($file && !empty($folder_id)) {
                $empty = new EmptyContentModel();
                $newFile = $module->attachFile($file->path, $empty, 'file', false, true, false, $file->name, $file->id);
                $newFile->detachBehavior('SoftDeleteByBehavior');
                $hash = substr(md5(rand()), 0, 8);
                $newFile->name = $file->name . '_' . $hash;
                $newFile->attribute = 'file';
                $newFile->item_id = 1;
                $newFile->save(false);

//                $crop_0 = new File();
//                $crop_0->detachBehavior('SoftDeleteByBehavior');
//                $crop_0->load($attributes);
//                $crop_0->name = '0_' . $newFile->name;
//                $crop_0->save(false);


                $storage = new AdminStorageFile();
                $storage->detachBehavior('SoftDeleteByBehavior');
                $storage->detachBehavior('TimestampBehavior');
                $storage->detachBehavior('BlameableBehavior');
                $storage->is_hidden = 0;
                $storage->folder_id = $folder_id;
                $storage->name_original = $file->name . '.' . $newFile->type;
                $storage->name_new = $newFile->name;
                $storage->name_new_compound = $newFile->name.'.' . $newFile->type;
                $storage->mime_type = $newFile->mime;
                $storage->extension = $newFile->type;
                $storage->hash_file = hash('md5', rand());
                $storage->hash_name = $hash;
                $storage->upload_timestamp = $newFile->date_upload;
                $storage->file_size = $newFile->size;
                $storage->upload_user_id = 1;
                $storage->save(false);

                if (isset(\Yii::$app->cache)) {
                    \Yii::$app->cache->flush();
                }
            }
        }

    }

}
