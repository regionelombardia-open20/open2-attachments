<?php

namespace open20\amos\attachments\models\search;

use open20\amos\tag\models\Tag;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\attachments\models\AttachGalleryImage;
use yii\helpers\ArrayHelper;

/**
 * AttachGalleryImageSearch represents the model behind the search form about `open20\amos\attachments\models\AttachGalleryImage`.
 */
class AttachGalleryImageSearch extends AttachGalleryImage
{
    public $customTagsSearch;
    public $tagsImageSearch;
    public $aspectRatioSearch;

//private $container; 

    public function __construct(array $config = [])
    {
//        $this->isSearch = true;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['id', 'category_id', 'gallery_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'description', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['aspectRatioSearch', 'tagsImageSearch', 'customTagsSearch', 'AttachGallery'], 'safe'],
            ['AttachGalleryCategory', 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @param $params
     * @param null $gallery_id
     * @return ActiveDataProvider
     */
    public function search($params, $gallery_id = null)
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
                ], 'attachGalleryCategory' => [
                    'asc' => ['attach_gallery_category.name' => SORT_ASC],
                    'desc' => ['attach_gallery_category.name' => SORT_DESC],
                ],]]);

        $query->andFilterWhere(['gallery_id' => $gallery_id]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if(!empty($this->tagsImageSearch) || !empty($this->customTagsSearch)){
            $query->leftJoin('entitys_tags_mm', 'attach_gallery_image.id = entitys_tags_mm.record_id')
                ->leftJoin('tag', 'tag.id = entitys_tags_mm.tag_id')
                ->leftJoin('tag as tag_2', 'tag_2.id = entitys_tags_mm.tag_id')
                ->andWhere(['entitys_tags_mm.classname' => AttachGalleryImage::className()])
                ->andWhere(['entitys_tags_mm.deleted_at' => null]);

            $tagIds = $this->tagsImageSearch;
//            if(!empty($tagIdImage)) {
//                $tagIdImage = ArrayHelper::merge(['0'], $tagIdImage);
//                $query->andFilterWhere(['in', 'tag_2.id', $tagIdImage]);
//            }


            // tag liberi
            if(!empty($this->customTagsSearch)){
                $tagNames = explode(',', $this->customTagsSearch);
//                $tagIds = [];
                foreach ($tagNames as $name){
                   $tag = Tag::find()->andWhere(['nome' => $name])->one();
                   if($tag) {
                       $tagIds [] = $tag->id;
                   }
                }
            }


            if(!empty($tagIds)) {
                $query->andFilterWhere(['in','tag.id', $tagIds]);
            } else {
                $query->andWhere(0);
            }


        }

        if(!empty($this->aspectRatioSearch)){
            if($this->aspectRatioSearch == 'other'){
                $query->andFilterWhere([ 'not in', 'attach_gallery_image.aspect_ratio' , ['1', '1.7']]);
            } else {
                $query->andFilterWhere([ 'attach_gallery_image.aspect_ratio' => $this->aspectRatioSearch]);
            }
        }

        $query->andFilterWhere([ '>= ', 'attach_gallery_image.created_at', $this->created_at]);
        $query->andFilterWhere([
            'attach_gallery_image.id' => $this->id,
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
//pr($query->createCommand()->rawSql);
        return $dataProvider;
    }
}
