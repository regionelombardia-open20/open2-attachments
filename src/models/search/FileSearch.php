<?php

namespace open20\amos\attachments\models\search;

use open20\amos\attachments\models\File;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class FileSearch extends File
{

    public $creator_id;
//private $container; 

    public function __construct(array $config = [])
    {
//        $this->isSearch = true;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name','model','attribute','item_id','hash','type','mime','creator_id', 'table_name_form'], 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = File::find();

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => [
                    'pageSize' => 300
                ]
            ]
        );

        $dataProvider->setSort(
            [
                'attributes' => [
                    'name' => [
                        'asc' => ['attach_file.name' => SORT_ASC],
                        'desc' => ['attach_file.name' => SORT_DESC],
                    ],
                    'model' => [
                        'asc' => ['attach_file.model' => SORT_ASC],
                        'desc' => ['attach_file.model' => SORT_DESC],
                    ],
                    'attribute' => [
                        'asc' => ['attach_file.attribute' => SORT_ASC],
                        'desc' => ['attach_file.attribute' => SORT_DESC],
                    ],
                ]
            ]
        );

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if($this->creator_id) {
            $records = $this->getByCreator($this->creator_id);

            $query->andWhere(['id' => $records]);
        }

        $query->andFilterWhere(
            [
                'id' => $this->id,
                'name' => $this->name,
                'model' => $this->model,
                'attribute' => $this->attribute,
                'item_id' => $this->item_id,
                'hash' => $this->hash,
                'size' => $this->size,
                'type' => $this->type,
                'mime' => $this->mime,
            ]
        );

        return $dataProvider;
    }

    public function getByCreator($userId) {
        $query =  File::find();

        $query->andFilterWhere(
            [
                'id' => $this->id,
                'name' => $this->name,
                'model' => $this->model,
                'attribute' => $this->attribute,
                'item_id' => $this->item_id,
                'hash' => $this->hash,
                'size' => $this->size,
                'type' => $this->type,
                'mime' => $this->mime,
            ]
        );

        $records = $query->all();
        $result = [];

        foreach ($records as $record) {
            if($record->owner && $record->owner->created_by == $userId) {
                $result[] = $record->id;
            }
        }

        return $result;
    }
}
