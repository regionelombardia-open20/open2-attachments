<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-attachments
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

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\attachments\models\AttachFileCategorie;

/**
 * AttachFileCategorieSearch represents the model behind the search form about `open20\amos\attachments\models\AttachFileCategorie`.
 */
class AttachFileCategorieSearch extends AttachFileCategorie
{

//private $container;

    public function __construct(array $config = [])
    {
        $this->isSearch = true;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['titolo', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = AttachFileCategorie::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'template' => [
                    'asc' => ['attach_file_categorie.template' => SORT_ASC],
                    'desc' => ['attach_file_categorie.template' => SORT_DESC],
                ],
                'vendorPath' => [
                    'asc' => ['attach_file_categorie.vendorPath' => SORT_ASC],
                    'desc' => ['attach_file_categorie.vendorPath' => SORT_DESC],
                ],
                'providerList' => [
                    'asc' => ['attach_file_categorie.providerList' => SORT_ASC],
                    'desc' => ['attach_file_categorie.providerList' => SORT_DESC],
                ],
                'actionButtonClass' => [
                    'asc' => ['attach_file_categorie.actionButtonClass' => SORT_ASC],
                    'desc' => ['attach_file_categorie.actionButtonClass' => SORT_DESC],
                ],
                'viewPath' => [
                    'asc' => ['attach_file_categorie.viewPath' => SORT_ASC],
                    'desc' => ['attach_file_categorie.viewPath' => SORT_DESC],
                ],
                'pathPrefix' => [
                    'asc' => ['attach_file_categorie.pathPrefix' => SORT_ASC],
                    'desc' => ['attach_file_categorie.pathPrefix' => SORT_DESC],
                ],
                'savedForm' => [
                    'asc' => ['attach_file_categorie.savedForm' => SORT_ASC],
                    'desc' => ['attach_file_categorie.savedForm' => SORT_DESC],
                ],
                'formLayout' => [
                    'asc' => ['attach_file_categorie.formLayout' => SORT_ASC],
                    'desc' => ['attach_file_categorie.formLayout' => SORT_DESC],
                ],
                'accessFilter' => [
                    'asc' => ['attach_file_categorie.accessFilter' => SORT_ASC],
                    'desc' => ['attach_file_categorie.accessFilter' => SORT_DESC],
                ],
                'generateAccessFilterMigrations' => [
                    'asc' => ['attach_file_categorie.generateAccessFilterMigrations' => SORT_ASC],
                    'desc' => ['attach_file_categorie.generateAccessFilterMigrations' => SORT_DESC],
                ],
                'singularEntities' => [
                    'asc' => ['attach_file_categorie.singularEntities' => SORT_ASC],
                    'desc' => ['attach_file_categorie.singularEntities' => SORT_DESC],
                ],
                'modelMessageCategory' => [
                    'asc' => ['attach_file_categorie.modelMessageCategory' => SORT_ASC],
                    'desc' => ['attach_file_categorie.modelMessageCategory' => SORT_DESC],
                ],
                'controllerClass' => [
                    'asc' => ['attach_file_categorie.controllerClass' => SORT_ASC],
                    'desc' => ['attach_file_categorie.controllerClass' => SORT_DESC],
                ],
                'modelClass' => [
                    'asc' => ['attach_file_categorie.modelClass' => SORT_ASC],
                    'desc' => ['attach_file_categorie.modelClass' => SORT_DESC],
                ],
                'searchModelClass' => [
                    'asc' => ['attach_file_categorie.searchModelClass' => SORT_ASC],
                    'desc' => ['attach_file_categorie.searchModelClass' => SORT_DESC],
                ],
                'baseControllerClass' => [
                    'asc' => ['attach_file_categorie.baseControllerClass' => SORT_ASC],
                    'desc' => ['attach_file_categorie.baseControllerClass' => SORT_DESC],
                ],
                'indexWidgetType' => [
                    'asc' => ['attach_file_categorie.indexWidgetType' => SORT_ASC],
                    'desc' => ['attach_file_categorie.indexWidgetType' => SORT_DESC],
                ],
                'enableI18N' => [
                    'asc' => ['attach_file_categorie.enableI18N' => SORT_ASC],
                    'desc' => ['attach_file_categorie.enableI18N' => SORT_DESC],
                ],
                'enablePjax' => [
                    'asc' => ['attach_file_categorie.enablePjax' => SORT_ASC],
                    'desc' => ['attach_file_categorie.enablePjax' => SORT_DESC],
                ],
                'messageCategory' => [
                    'asc' => ['attach_file_categorie.messageCategory' => SORT_ASC],
                    'desc' => ['attach_file_categorie.messageCategory' => SORT_DESC],
                ],
                'formTabs' => [
                    'asc' => ['attach_file_categorie.formTabs' => SORT_ASC],
                    'desc' => ['attach_file_categorie.formTabs' => SORT_DESC],
                ],
                'tabsFieldList' => [
                    'asc' => ['attach_file_categorie.tabsFieldList' => SORT_ASC],
                    'desc' => ['attach_file_categorie.tabsFieldList' => SORT_DESC],
                ],
                'relFiledsDynamic' => [
                    'asc' => ['attach_file_categorie.relFiledsDynamic' => SORT_ASC],
                    'desc' => ['attach_file_categorie.relFiledsDynamic' => SORT_DESC],
                ],
        ]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
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

        $query->andFilterWhere(['like', 'titolo', $this->titolo]);

        return $dataProvider;
    }
}