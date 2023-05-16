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
use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\tag\models\Tag;
use open20\amos\workflow\behaviors\WorkflowLogFunctionsBehavior;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "attach_gallery_request".
 */
class AttachGalleryRequest
    extends
        \open20\amos\attachments\models\base\AttachGalleryRequest
{
    // Workflow ID
    const GALLERY_IMAGE_REQUEST_WORKFLOW = 'GalleryImageRequestWorkflow';

    // Workflow states IDS
    const IMAGE_REQUEST_WORKFLOW_STATUS_OPENED = 'GalleryImageRequestWorkflow/OPENED';
    const IMAGE_REQUEST_WORKFLOW_STATUS_CLOSED = 'GalleryImageRequestWorkflow/CLOSED';

    /**
     * 
     */
    const SCENARIO_REPLY = 'scenario_reply';

    /**
     * 
     * @var type
     */
    public $tagsImage;
    
    /**
     * 
     * @var type
     */
    public $customTags;
    
    /**
     * 
     * @var type
     */
    public $customTagsReply;

    /**
     * 
     * @return type
     */
    public function representingColumn()
    {
        return ['title'];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        $parentScenarios = parent::scenarios();
        $scenarios = ArrayHelper::merge(
            $parentScenarios,
            [
                self::SCENARIO_REPLY => ['text_reply', 'customTagsReply', 'aspect_ratio_reply']
            ]
        );
        return $scenarios;
    }

    /**
     * 
     * @return type
     */
    public function attributeHints()
    {
        return [
        ];
    }

    /**
     *
     */
    public function init()
    {
        if ($this->isNewRecord) {
            $this->status = $this->getWorkflowSource()->getWorkflow(
                self::GALLERY_IMAGE_REQUEST_WORKFLOW
            )
            ->getInitialStatusId();
        }
        
        parent::init();
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

    /**
     * 
     * @return type
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['attachImage', 'file'],
            [['attachImage', 'tagsImage', 'customTags', 'customTagsReply'], 'safe']
        ]);
    }

    /**
     *
     */
    public function validateFiles()
    {
        foreach ((Array)$_FILES['AttachGalleryRequest']['name'] as $attribute => $filename) {
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

    /**
     * 
     * @return type
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(
            parent::attributeLabels(),
            []
        );
    }

    /**
     * 
     * @return type
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'fileBehavior' => [
                    'class' => FileBehavior::class
                ],
                'workflow' => [
                    'class' => SimpleWorkflowBehavior::class,
                    'defaultWorkflowId' => self::GALLERY_IMAGE_REQUEST_WORKFLOW,
                    'propagateErrorsToModel' => true
                ],
                'WorkflowLogFunctionsBehavior' => [
                    'class' => WorkflowLogFunctionsBehavior::class,
                ],
            ]
        );
    }

    /**
     * 
     * @return type
     */
    public function getEditFields()
    {
        $labels = $this->attributeLabels();

        return [
            [
                'slug' => 'title',
                'label' => $labels['title'],
                'type' => 'string'
            ],
            [
                'slug' => 'status',
                'label' => $labels['status'],
                'type' => 'string'
            ],
            [
                'slug' => 'aspect_ratio',
                'label' => $labels['aspect_ratio'],
                'type' => 'string'
            ],
            [
                'slug' => 'text_request',
                'label' => $labels['text_request'],
                'type' => 'text'
            ],
            [
                'slug' => 'text_reply',
                'label' => $labels['text_reply'],
                'type' => 'text'
            ],
            [
                'slug' => 'attach_gallery_id',
                'label' => $labels['attach_gallery_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'attach_gallery_image_id',
                'label' => $labels['attach_gallery_image_id'],
                'type' => 'integer'
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
        $root = Tag::find()->andWhere(['codice' => AttachGalleryImage::ROOT_TAG_CUSTOM])->one();
        if ($root) {
            EntitysTagsMm::deleteAll([
                'root_id' => $root->id,
                'record_id' =>
                $this->id,
                'classname' => AttachGalleryRequest::class
            ]);
            $exploded = explode(',', $this->customTags);
            $exploded = array_unique($exploded);
            foreach ($exploded as $tagString) {
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
                    $tagsMm->classname = AttachGalleryRequest::class;
                    $tagsMm->save(false);
                }
            }
        }
    }

    /**
     *
     */
    public function saveCustomTagsReply()
    {
        $root = Tag::find()->andWhere(['codice' => AttachGalleryImage::ROOT_TAG_CUSTOM])->one();
        if ($root) {
            $exploded = explode(',', $this->customTagsReply);
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
                        $tagsMm->classname = AttachGalleryRequest::class;
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

        $root = Tag::find()->andWhere([
            'codice' => AttachGalleryImage::ROOT_TAG_CUSTOM
        ])
        ->one();
        
        if ($root) {
            $tagsMm = EntitysTagsMm::find()
                ->innerJoin('tag', 'tag.id = entitys_tags_mm.tag_id')
                ->andWhere(['classname' => AttachGalleryRequest::class])
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
                EntitysTagsMm::deleteAll([
                    'root_id' => $root->id,
                    'record_id' => $this->id,
                    'classname' => AttachGalleryRequest::class
                ]);
                foreach ($this->tagsImage as $tagId) {
                    $tagsMm = new EntitysTagsMm();
                    $tagsMm->tag_id = $tagId;
                    $tagsMm->record_id = $this->id;
                    $tagsMm->root_id = $root->id;
                    $tagsMm->classname = AttachGalleryRequest::class;
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
                    ->andWhere(['classname' => AttachGalleryRequest::class])
                    ->all();
                foreach ($tagsMm as $tagMm) {
                    $this->tagsImage [] = $tagMm->tag_id;
                }
            }
        }
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
     * @throws \yii\base\InvalidConfigException
     */
    public function createAttachImageGallery()
    {
        $image = new AttachGalleryImage();
        $image->gallery_id = $this->attach_gallery_id;
        $image->name = $this->title;
        $image->aspect_ratio = $this->aspect_ratio_reply;
        $image->save(false);
        $moduleAttachments = \Yii::$app->getModule('attachments');
        /** @var  $attachImage File */
        $attachImage = $this->attachImage;
        if ($attachImage) {
            $moduleAttachments->attachFile($attachImage->getPath(), $image, 'attachImage', false);
        }

        $this->loadCustomTags();
        $this->loadTagsImage();

        $image->customTags = $this->customTags;
        $image->tagsImage = $this->tagsImage;
        $image->saveImageTags();
        $image->saveCustomTags();
        return $image;

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

}
