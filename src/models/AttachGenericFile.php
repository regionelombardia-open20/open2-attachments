<?php

namespace open20\amos\attachments\models;

use open20\amos\admin\models\UserProfile;
use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\tag\models\Tag;
use yii\helpers\ArrayHelper;

class AttachGenericFile extends File
{

    const TYPE_BACKEND_FILE = 'open20\amos\attachments\models\AttachDatabankFile';
    const TYPE_LUYA_FILE = 'open20\amos\attachments\models\EmptyContentModel';

    public $customTags;
    public $extension;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['extension', 'safe']
        ]);
    }


    /**
     * @return AttachDatabankFile|null
     */
    public function getRealModel()
    {
        $model = null;
        if ($this->model == self::TYPE_BACKEND_FILE) {
            $model = AttachDatabankFile::findOne($this->item_id);
        } else {
            AdminStorageFile::find()->andWhere([$this->name]);
        }
        return $model;
    }

    public function isBackendFile()
    {
        if ($this->model == self::TYPE_BACKEND_FILE) {
            return true;
        }
        return false;
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

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function loadCustomTags()
    {
        $this->customTags = [];
        $model = $this->getRealModel();
        $root = Tag::find()->andWhere(['codice' => AttachDatabankFile::ROOT_TAG_DATABANK_FILE])->one();
        if ($root) {
            $tagsMm = EntitysTagsMm::find()
                ->innerJoin('tag', 'tag.id = entitys_tags_mm.tag_id')
                ->andWhere(['classname' => AttachDatabankFile::class])
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

}