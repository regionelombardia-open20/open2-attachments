<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\attachments\models\search
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
 */

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
