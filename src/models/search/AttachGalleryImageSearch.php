<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\models\search
 * @category   CategoryName
 */

namespace open20\amos\attachments\models\search;

use open20\amos\attachments\models\AttachGenericImage;
use open20\amos\tag\models\Tag;
use open20\amos\attachments\models\AttachGalleryImage;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * AttachGalleryImageSearch represents the model behind the search form about `open20\amos\attachments\models\AttachGalleryImage`.
 */
class AttachGalleryImageSearch extends AttachGalleryImage
{
    /**
     *
     * @var type
     */
    public $customTagsSearch;

    /**
     *
     * @var type
     */
    public $tagsImageSearch;

    /**
     *
     */
    public $aspectRatioSearch;

    /**
     * @var
     */
    public $uploadData;

    /**
     *
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     *
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'gallery_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'description', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['uploadData', 'aspectRatioSearch', 'tagsImageSearch', 'customTagsSearch', 'AttachGallery'], 'safe'],
            ['AttachGalleryCategory', 'safe'],
        ];
    }

    /**
     *
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * @param $params
     * @param null $gallery_id
     * @return ActiveDataProvider
     */
    public function search($params, $gallery_id = null, $onlyQuery = false)
    {
        $query = AttachGalleryImage::find();
        $query->innerJoin('user_profile', 'user_profile.user_id = attach_gallery_image.created_by');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith('gallery');
        $query->joinWith('category');

        $dataProvider->setSort([
            'attributes' => [
                'name' => [
                    'asc' => ['attach_gallery_image.name' => SORT_ASC],
                    'desc' => ['attach_gallery_image.name' => SORT_DESC],
                ],
                'description' => [
                    'asc' => ['attach_gallery_image.description' => SORT_ASC],
                    'desc' => ['attach_gallery_image.description' => SORT_DESC],
                ],
                'category_id' => [
                    'asc' => ['attach_gallery_image.category_id' => SORT_ASC],
                    'desc' => ['attach_gallery_image.category_id' => SORT_DESC],
                ],
                'gallery_id' => [
                    'asc' => ['attach_gallery_image.gallery_id' => SORT_ASC],
                    'desc' => ['attach_gallery_image.gallery_id' => SORT_DESC],
                ],
                'created_at' => [
                    'asc' => ['attach_gallery_image.created_at' => SORT_ASC],
                    'desc' => ['attach_gallery_image.created_at' => SORT_DESC],
                ],
                'created_by' => [
                    'asc' => ['user_profile.nome' => SORT_ASC],
                    'desc' => ['user_profile.nome' => SORT_DESC],
                ],
                'attachGallery' => [
                    'asc' => ['attach_gallery.name' => SORT_ASC],
                    'desc' => ['attach_gallery.name' => SORT_DESC],
                ],
                'attachGalleryCategory' => [
                    'asc' => ['attach_gallery_category.name' => SORT_ASC],
                    'desc' => ['attach_gallery_category.name' => SORT_DESC],
                ],
            ]
        ]);

        $query->andFilterWhere(['gallery_id' => $gallery_id]);

        if (!($this->load($params) && $this->validate())) {
            if ($onlyQuery) {
                return $query;
            }
            return $dataProvider;
        }

        if (!empty($this->tagsImageSearch) || !empty($this->customTagsSearch)) {
            $query->leftJoin('entitys_tags_mm', 'attach_gallery_image.id = entitys_tags_mm.record_id')
                ->leftJoin('tag', 'tag.id = entitys_tags_mm.tag_id')
                ->leftJoin('tag as tag_2', 'tag_2.id = entitys_tags_mm.tag_id')
                ->andWhere(['entitys_tags_mm.classname' => AttachGalleryImage::class])
                ->andWhere(['entitys_tags_mm.deleted_at' => null]);

            // tag liberi
            $tagIds = $this->tagsImageSearch;
            if (!empty($this->customTagsSearch)) {
                $tagNames = explode(',', $this->customTagsSearch);
                foreach ($tagNames as $name) {
                    $tags = Tag::find()->select('id')->andWhere(['nome' => $name])->asArray()->all();
                    if ($tags) {
                        foreach ($tags as $tag) {
                            $tagIds[] = $tag['id'];
                        }
                    }
                }
            }

            if (!empty($tagIds)) {
                $query->andFilterWhere(['in', 'tag.id', $tagIds]);
            } else {
                $query->andWhere(0);
            }
        }

        if (!empty($this->aspectRatioSearch)) {
            if ($this->aspectRatioSearch == 'other') {
                $query->andFilterWhere(['not in', 'attach_gallery_image.aspect_ratio', ['1', '1.7']]);
            } else {
                $query->andFilterWhere(['attach_gallery_image.aspect_ratio' => $this->aspectRatioSearch]);
            }
        }

        $query->andFilterWhere(['>= ', 'attach_gallery_image.created_at', $this->created_at]);
        $query->andFilterWhere(['attach_gallery_image.id' => $this->id,
            'attach_gallery_image.category_id' => $this->category_id,
            'attach_gallery_image.gallery_id' => $this->gallery_id,
            'attach_gallery_image.updated_at' => $this->updated_at,
            'attach_gallery_image.deleted_at' => $this->deleted_at,
            'attach_gallery_image.created_by' => $this->created_by,
            'attach_gallery_image.updated_by' => $this->updated_by,
            'attach_gallery_image.deleted_by' => $this->deleted_by,
        ]);

        $query
            ->andFilterWhere(['like', 'attach_gallery_image.name', $this->name])
            ->andFilterWhere(['like', 'attach_gallery_image.description', $this->description]);

        if ($onlyQuery) {
            return $query;
        }
        return $dataProvider;
    }

    /**
     * @param $params
     * @return void|ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function searchGenericFiles($params = [], $gallery_id = null)
    {
        $module = \Yii::$app->getModule('attachments');
        $query = AttachGenericImage::find();
        $query->andWhere(['OR',
                ['model' => AttachGenericImage::TYPE_BACKEND_FILE],
                ['model' => AttachGenericImage::TYPE_LUYA_FILE]
            ]
        )
        ->andWhere(['original_attach_file_id' => null]);

        $query->andWhere(['OR',
            ['AND',
                ['NOT LIKE', 'name', '0_%', false],
                ['NOT LIKE', 'name', '4_%', false],
                ['NOT LIKE', 'name', '8_%', false],
                ['type' => ['jpg', 'png', 'gif', 'bitmap', 'tiff']],
                ['model' => AttachGenericImage::TYPE_LUYA_FILE],
            ],
            ['model' => AttachGenericImage::TYPE_BACKEND_FILE]
        ]);

        if ($module && !$module->showLuyaGallery) {
            $query->andWhere(['!=', 'model', AttachGenericImage::TYPE_LUYA_FILE]);
        }

//        echo ($query->createCommand()->rawSql);die;

//        $subquery = AttachGenericFile::find()
//            ->select('attach_file.id')
//            ->innerJoin('admin_storage_file',new Expression( "name_new_compound = CONCAT(attach_file.name,'.',attach_file.type)"))
//            ->andWhere(['!=','admin_storage_file.is_deleted', 1]);
//
//        $query->andWhere(['OR',
//            ['AND',
//                ['attach_file.id' => $subquery],
//                ['model' => AttachGenericFile::TYPE_LUYA_FILE],
//            ],
//            ['model' => AttachGenericFile::TYPE_BACKEND_FILE]
//        ]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id' => [
                    'asc' => ['attach_file.id' => SORT_ASC],
                    'desc' => ['attach_file.id' => SORT_DESC],
                ],
                'name' => [
                    'asc' => ['attach_file.name' => SORT_ASC],
                    'desc' => ['attach_file.name' => SORT_DESC],
                ],
                'date_upload' => [
                    'asc' => ['attach_file.date_upload' => SORT_ASC],
                    'desc' => ['attach_file.date_upload' => SORT_DESC],
                ],
            ],
            'defaultOrder' => [
                'date_upload' => SORT_DESC,
            ]
        ]);


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if (!empty($params)) {
            $subQuery = $this->search($params, $gallery_id, true);
            $galleryImageIds = $subQuery->select('attach_gallery_image.id')->asArray()->column();
            if (!empty($this->aspectRatioSearch) || !empty($this->tagsImageSearch) || !empty($this->customTagsSearch) || !empty($this->created_by)) {
                $query->andWhere([
                    'attach_file.item_id' => $galleryImageIds,
                    'model' => AttachGenericImage::TYPE_BACKEND_FILE,
                ]);
            } else {
                $query->andWhere(['OR',
                    ['AND',
                        ['attach_file.item_id' => $galleryImageIds],
                        ['model' => AttachGenericImage::TYPE_BACKEND_FILE],
                    ],
                    ['model' => AttachGenericImage::TYPE_LUYA_FILE]
                ]);
            }
        }
        if (!empty($this->name)) {
            $query->andWhere(['OR',
                ['AND',
                    ['like', 'name', $this->name],
                    ['model' => AttachGenericImage::TYPE_LUYA_FILE],
                ],
                ['model' => AttachGenericImage::TYPE_BACKEND_FILE]
            ]);
        }


        $query->andFilterWhere(['created_by' => $this->created_by]);
        if (!empty($this->uploadData)) {
            $timestamp = strtotime($this->uploadData);
            $query->andFilterWhere(['>=', 'date_upload', $timestamp]);
        }
        return $dataProvider;
    }


}
