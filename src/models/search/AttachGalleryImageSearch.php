<?php

namespace open20\amos\attachments\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\attachments\models\AttachGalleryImage;

/**
 * AttachGalleryImageSearch represents the model behind the search form about `open20\amos\attachments\models\AttachGalleryImage`.
 */
class AttachGalleryImageSearch extends AttachGalleryImage
{

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
            ['AttachGallery', 'safe'],
            ['AttachGalleryCategory', 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = AttachGalleryImage::find();

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
                'attachGallery' => [
                    'asc' => ['attach_gallery.name' => SORT_ASC],
                    'desc' => ['attach_gallery.name' => SORT_DESC],
                ], 'attachGalleryCategory' => [
                    'asc' => ['attach_gallery_category.name' => SORT_ASC],
                    'desc' => ['attach_gallery_category.name' => SORT_DESC],
                ],]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'gallery_id' => $this->gallery_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);
        $query->andFilterWhere(['like', new \yii\db\Expression('attach_gallery.name'), $this->AttachGallery]);
        $query->andFilterWhere(['like', new \yii\db\Expression('attach_gallery_category.name'), $this->AttachGalleryCategory]);

        return $dataProvider;
    }
}
