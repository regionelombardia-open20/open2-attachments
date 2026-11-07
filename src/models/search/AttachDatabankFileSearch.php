<?php

namespace open20\amos\attachments\models\search;

use open20\amos\admin\models\UserProfile;
use open20\amos\attachments\FileModule;
use open20\amos\attachments\models\File;
use open20\amos\attachments\models\AttachGalleryImage;
use open20\amos\attachments\models\AttachGenericFile;
use open20\amos\tag\models\Tag;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\attachments\models\AttachDatabankFile;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * AttachDatabankFileSearch represents the model behind the search form about `open20\amos\attachments\models\AttachDatabankFile`.
 */
class AttachDatabankFileSearch extends AttachDatabankFile
{

//private $container; 

    public $customTagsSearch;
    public $extension;
    public $uploadData;
    public $attachmentCatSearch;


    public function __construct(array $config = [])
    {
        $this->isSearch = true;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['attachmentCatSearch', 'uploadData', 'extension', 'customTagsSearch', 'name', 'extension', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'attachmentCatSearch' => FileModule::t('amosattachments',
                'Categoria')
        ]);
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
        
        $tabDatabank = AttachDatabankFile::tableName();
        
        $dataProvider->setSort([
            'attributes' => [
                'filename' => [
                    'asc' => [$tabDatabank . '.name' => SORT_ASC],
                    'desc' => [$tabDatabank . '.name' => SORT_DESC],
                ],
                'extension' => [
                    'asc' => [$tabDatabank . '.extension' => SORT_ASC],
                    'desc' => [$tabDatabank . '.extension' => SORT_DESC],
                ],
            ]]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if (!empty($this->customTagsSearch)) {
            $query->leftJoin('entitys_tags_mm', $tabDatabank . '.id = entitys_tags_mm.record_id')
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

        if (!empty($this->attachmentCatSearch)) {
            $tabFile = File::tableName();
            $query->leftJoin($tabFile)
                ->andWhere([$tabFile . '.model' => AttachDatabankFile::class])
                ->andWhere([$tabFile . '.attach_file_categorie_id'
                    => $this->attachmentCatSearch]);
        }

        $query->andFilterWhere([
            $tabDatabank . '.id' => $this->id,
            $tabDatabank . '.created_at' => $this->created_at,
            $tabDatabank . '.updated_at' => $this->updated_at,
            $tabDatabank . '.deleted_at' => $this->deleted_at,
            $tabDatabank . '.created_by' => $this->created_by,
            $tabDatabank . '.updated_by' => $this->updated_by,
            $tabDatabank . '.deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', $tabDatabank . '.name', $this->name])
            ->andFilterWhere(['like', $tabDatabank . '.extension',
                $this->extension]);
        
        return $dataProvider;
    }


    /**
     * @param $params
     * @return void|ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function searchGenericFiles($params)
    {
        // Inizializzazione di $params se inesistente per evitare problemi con il metodo load()
        if(!isset($params['AttachDatabankFileSearch'])) {
            $params = ArrayHelper::merge( $params, [
                    'AttachDatabankFileSearch' => []
            ]);
        }

        $tabFile = AttachGenericFile::tableName();

        $query = AttachGenericFile::find();
        $query->andWhere(['OR',
                [$tabFile . '.model' => AttachGenericFile::TYPE_BACKEND_FILE],
                [$tabFile . '.model' => AttachGenericFile::TYPE_LUYA_FILE]
            ]
        )->andWhere(['original_attach_file_id' => null]);

        $query->andWhere(['OR',
            ['AND',
                ['NOT LIKE', $tabFile . '.name', '0_%', false],
                ['NOT LIKE', $tabFile . '.name', '4_%', false],
                ['NOT LIKE', $tabFile . '.name', '8_%', false],
                [$tabFile . '.model' => AttachGenericFile::TYPE_LUYA_FILE],
            ],
            [$tabFile . '.model' => AttachGenericFile::TYPE_BACKEND_FILE]
        ]);
        
        $fileNotExits = \Yii::$app->db->createCommand("select attach_file_id from attach_file_not_exists where deleted_at is null and check_ok = 0")->rawSql;
        $query->andWhere(new \yii\db\Expression("attach_file.id not in ($fileNotExits)"));


//        $subquery = AttachGenericFile::find()
//            ->select($tabFile . '.id')
//            ->innerJoin('admin_storage_file',new Expression( "name_new_compound = CONCAT(attach_file.name,'.',attach_file.type)"))
//            ->andWhere(['!=','admin_storage_file.is_deleted', 1]);
//
//        $query->andWhere(['OR',
//            ['AND',
//                [$tabFile . '.id' => $subquery],
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
                    'asc' => [$tabFile . '.id' => SORT_ASC],
                    'desc' => [$tabFile . '.id' => SORT_DESC],
                ],
                'name' => [
                    'asc' => [$tabFile . '.name' => SORT_ASC],
                    'desc' => [$tabFile . '.name' => SORT_DESC],
                ],
                'date_upload' => [
                    'asc' => [$tabFile . '.date_upload' => SORT_ASC],
                    'desc' => [$tabFile . '.date_upload' => SORT_DESC],
                ],
            ],
            'defaultOrder' => [
                'date_upload' => SORT_DESC,
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        
        if(is_null($this->extension)) {
            $whiteListExtensions = (isset($params['extensions']) && $params['extensions'] != null) ? $params['extensions'] :
                    Yii::$app->getModule('attachments')->whiteListExtensions;
            if($whiteListExtensions) {
                $query->andFilterWhere(['or like', 'type', $whiteListExtensions]);
            }
        }

        $query->andFilterWhere(['like', $tabFile . '.name', $this->name])
            ->andFilterWhere(['like', $tabFile . '.type', $this->extension])
            ->andFilterWhere([$tabFile . '.created_by' => $this->created_by])
            ->andFilterWhere([$tabFile . '.attach_file_categorie_id'
                => $this->attachmentCatSearch]);

        if (!empty($this->uploadData)) {
            $timestamp = strtotime($this->uploadData);
            $query->andFilterWhere(['>=', $tabFile . '.date_upload', $timestamp]);
        }

        if (!empty($this->customTagsSearch)) {
            $query->leftJoin('entitys_tags_mm', $tabFile . '.item_id = entitys_tags_mm.record_id')
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
                $query->andWhere([$tabFile . '.model' => AttachGenericFile::TYPE_BACKEND_FILE]);
            } else {
                $query->andWhere(0);
            }
        }
        return $dataProvider;
    }


}
