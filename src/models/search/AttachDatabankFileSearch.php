<?php

namespace open20\amos\attachments\models\search;

use open20\amos\admin\models\UserProfile;
use open20\amos\attachments\models\AttachGalleryImage;
use open20\amos\attachments\models\AttachGenericFile;
use open20\amos\tag\models\Tag;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\attachments\models\AttachDatabankFile;
use yii\db\Expression;

/**
 * AttachDatabankFileSearch represents the model behind the search form about `open20\amos\attachments\models\AttachDatabankFile`.
 */
class AttachDatabankFileSearch extends AttachDatabankFile
{

//private $container; 

    public $customTagsSearch;
    public $extension;
    public $uploadData;


    public function __construct(array $config = [])
    {
        $this->isSearch = true;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['uploadData', 'extension', 'customTagsSearch', 'name', 'extension', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = AttachDatabankFile::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $dataProvider->setSort([
            'attributes' => [
                'filename' => [
                    'asc' => ['attach_databank_file.name' => SORT_ASC],
                    'desc' => ['attach_databank_file.name' => SORT_DESC],
                ],
                'extension' => [
                    'asc' => ['attach_databank_file.extension' => SORT_ASC],
                    'desc' => ['attach_databank_file.extension' => SORT_DESC],
                ],
            ]]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if (!empty($this->customTagsSearch)) {
            $query->leftJoin('entitys_tags_mm', 'attach_databank_file.id = entitys_tags_mm.record_id')
                ->leftJoin('tag', 'tag.id = entitys_tags_mm.tag_id')
                ->leftJoin('tag as tag_2', 'tag_2.id = entitys_tags_mm.tag_id')
                ->andWhere(['entitys_tags_mm.classname' => AttachDatabankFile::class])
                ->andWhere(['entitys_tags_mm.deleted_at' => null]);

            // tag liberi
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


        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'extension', $this->extension]);


        return $dataProvider;
    }


    /**
     * @param $params
     * @return void|ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function searchGenericFiles($params)
    {
        $query = AttachGenericFile::find();
        $query->andWhere(['OR',
                ['model' => AttachGenericFile::TYPE_BACKEND_FILE],
                ['model' => AttachGenericFile::TYPE_LUYA_FILE]
            ]
        )->andWhere(['original_attach_file_id' => null]);

        $query->andWhere(['OR',
            ['AND',
                ['NOT LIKE', 'name', '0_%', false],
                ['NOT LIKE', 'name', '4_%', false],
                ['NOT LIKE', 'name', '8_%', false],
                ['model' => AttachGenericFile::TYPE_LUYA_FILE],
            ],
            ['model' => AttachGenericFile::TYPE_BACKEND_FILE]
        ]);

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
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'type', $this->extension])
            ->andFilterWhere(['created_by' => $this->created_by]);

        if (!empty($this->uploadData)) {
            $timestamp = strtotime($this->uploadData);
            $query->andFilterWhere(['>=', 'date_upload', $timestamp]);
        }


        if (!empty($this->customTagsSearch)) {
            $query->leftJoin('entitys_tags_mm', 'attach_file.item_id = entitys_tags_mm.record_id')
                ->leftJoin('tag', 'tag.id = entitys_tags_mm.tag_id')
//                ->leftJoin('tag as tag_2', 'tag_2.id = entitys_tags_mm.tag_id')
                ->andWhere(['entitys_tags_mm.classname' => AttachDatabankFile::class])
                ->andWhere(['entitys_tags_mm.deleted_at' => null]);

            // tag liberi
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
                $query->andWhere(['model' => AttachGenericFile::TYPE_BACKEND_FILE]);
            } else {
                $query->andWhere(0);
            }
        }
        return $dataProvider;
    }


}
