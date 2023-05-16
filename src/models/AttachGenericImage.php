<?php

namespace open20\amos\attachments\models;

use open20\amos\admin\models\UserProfile;
use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\tag\models\Tag;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class AttachGenericImage extends File
{

    const TYPE_BACKEND_FILE = 'open20\amos\attachments\models\AttachGalleryImage';
    const TYPE_LUYA_FILE = 'open20\amos\attachments\models\EmptyContentModel';

    public $customTags;
    public $tagsImage;
    public $extension;
    public $originalModel = null;

    public function init()
    {
        parent::init();

    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['extension', 'safe']
        ]);
    }


    /**
     * @return AttachGalleryImage|null
     */
    public function getRealModel()
    {
        $model = null;
        if (empty($this->originalModel)) {
            if ($this->model == self::TYPE_BACKEND_FILE) {
                $model = AttachGalleryImage::findOne($this->item_id);
                $this->originalModel = $model;
            }
        }
//        }else{
////            AdminStorageFile::find()->andWhere([$this->name]);
//        }
        return $this->originalModel;
    }

    public function isBackendFile()
    {
        if ($this->model == self::TYPE_BACKEND_FILE) {
            return true;
        }
        return false;
    }


    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getCreatedByProfile()
    {
        $profile = null;
        if ($this->isBackendFile()) {
            $realModel = $this->getRealModel();
            if ($realModel) {
                $profile = UserProfile::find()->andWhere(['user_id' => $realModel->created_by])->one();
            }
        }
        if ($profile) {
            return $profile->nomeCognome;
        }
        return '-';
    }

    /**
     * @return \DateTime|false
     */
    public function getCreatedAt()
    {

        $date = date('d-m-Y H:i:s', $this->date_upload);
        $createdAt = new \DateTime($date);
        return $createdAt;
    }

    public function getGenericName()
    {
        if ($this->model == self::TYPE_BACKEND_FILE) {
            if ($this->getRealModel()) {
                return $this->getRealModel()->name;
            }
        }
        return $this->name;
    }


    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function getCustomTagsModel()
    {
        $tags = [];
        if ($this->isBackendFile()) {
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
        }
        return $tags;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function loadCustomTags()
    {
        $this->customTags = [];
        $model = $this->getRealModel();
        $root = Tag::find()->andWhere(['codice' => AttachGalleryImage::ROOT_TAG_CUSTOM])->one();
        if ($root) {
            $tagsMm = EntitysTagsMm::find()
                ->innerJoin('tag', 'tag.id = entitys_tags_mm.tag_id')
                ->andWhere(['classname' => AttachGalleryImage::class])
                ->andWhere(['record_id' => $model->id])
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
     * @throws \yii\base\InvalidConfigException
     */
    public function loadTagsImage()
    {
        $module = \Yii::$app->getModule('attachments');
        $codice = $module->codiceTagGallery;
        $model = $this->getRealModel();

        if ($codice) {
            $root = Tag::find()->andWhere(['codice' => $codice])->one();
            if ($root) {
                $tagsMm = EntitysTagsMm::find()
                    ->andWhere(['root_id' => $root->id])
                    ->andWhere(['record_id' => $model->id])
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
     * @throws \yii\base\InvalidConfigException
     */
    public function getTagsImageModel()
    {
        $tags = [];
        if ($this->isBackendFile()) {
            $this->loadTagsImage();
            foreach ($this->tagsImage as $tag_id) {
                $tag = Tag::findOne($tag_id);
                if ($tag) {
                    $tags [] = $tag;
                }
            }
        }
        return $tags;
    }


    /**
     * @inheritdoc
     */
    public function getFullLinkViewUrl()
    {
        if ($this->isBackendFile()) {
            return Url::toRoute(["/attachments/attach-gallery/view", "id" => $this->item_id]);
        } else {
            $ref = FileRefs::getRefByAttachFile($this, 'original', false);
            return Url::toRoute(["/attachments/file/view", "hash" => $ref->hash]);
        }

    }

    /**
     * @return bool
     */
    public function isFromShutterstock()
    {
        $realModel = $this->getRealModel();
        if ($realModel) {
            return !empty($realModel->shutterstock_image_id);
        }
        return false;
    }
}