<?php

namespace open20\amos\attachments\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\attachments\models\AttachGalleryCategory;

/**
 * AttachGalleryCategorySearch represents the model behind the search form about `open20\amos\attachments\models\AttachGalleryCategory`.
 */
class AttachGalleryCategorySearch extends AttachGalleryCategory
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
            [['id', 'default_order', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'description', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = AttachGalleryCategory::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $dataProvider->setSort([
            'attributes' => [
                'template' => [
                    'asc' => ['attach_gallery_category.template' => SORT_ASC],
                    'desc' => ['attach_gallery_category.template' => SORT_DESC],
                ],
                'vendorPath' => [
                    'asc' => ['attach_gallery_category.vendorPath' => SORT_ASC],
                    'desc' => ['attach_gallery_category.vendorPath' => SORT_DESC],
                ],
                'providerList' => [
                    'asc' => ['attach_gallery_category.providerList' => SORT_ASC],
                    'desc' => ['attach_gallery_category.providerList' => SORT_DESC],
                ],
                'actionButtonClass' => [
                    'asc' => ['attach_gallery_category.actionButtonClass' => SORT_ASC],
                    'desc' => ['attach_gallery_category.actionButtonClass' => SORT_DESC],
                ],
                'viewPath' => [
                    'asc' => ['attach_gallery_category.viewPath' => SORT_ASC],
                    'desc' => ['attach_gallery_category.viewPath' => SORT_DESC],
                ],
                'pathPrefix' => [
                    'asc' => ['attach_gallery_category.pathPrefix' => SORT_ASC],
                    'desc' => ['attach_gallery_category.pathPrefix' => SORT_DESC],
                ],
                'savedForm' => [
                    'asc' => ['attach_gallery_category.savedForm' => SORT_ASC],
                    'desc' => ['attach_gallery_category.savedForm' => SORT_DESC],
                ],
                'formLayout' => [
                    'asc' => ['attach_gallery_category.formLayout' => SORT_ASC],
                    'desc' => ['attach_gallery_category.formLayout' => SORT_DESC],
                ],
                'accessFilter' => [
                    'asc' => ['attach_gallery_category.accessFilter' => SORT_ASC],
                    'desc' => ['attach_gallery_category.accessFilter' => SORT_DESC],
                ],
                'generateAccessFilterMigrations' => [
                    'asc' => ['attach_gallery_category.generateAccessFilterMigrations' => SORT_ASC],
                    'desc' => ['attach_gallery_category.generateAccessFilterMigrations' => SORT_DESC],
                ],
                'singularEntities' => [
                    'asc' => ['attach_gallery_category.singularEntities' => SORT_ASC],
                    'desc' => ['attach_gallery_category.singularEntities' => SORT_DESC],
                ],
                'modelMessageCategory' => [
                    'asc' => ['attach_gallery_category.modelMessageCategory' => SORT_ASC],
                    'desc' => ['attach_gallery_category.modelMessageCategory' => SORT_DESC],
                ],
                'controllerClass' => [
                    'asc' => ['attach_gallery_category.controllerClass' => SORT_ASC],
                    'desc' => ['attach_gallery_category.controllerClass' => SORT_DESC],
                ],
                'modelClass' => [
                    'asc' => ['attach_gallery_category.modelClass' => SORT_ASC],
                    'desc' => ['attach_gallery_category.modelClass' => SORT_DESC],
                ],
                'searchModelClass' => [
                    'asc' => ['attach_gallery_category.searchModelClass' => SORT_ASC],
                    'desc' => ['attach_gallery_category.searchModelClass' => SORT_DESC],
                ],
                'baseControllerClass' => [
                    'asc' => ['attach_gallery_category.baseControllerClass' => SORT_ASC],
                    'desc' => ['attach_gallery_category.baseControllerClass' => SORT_DESC],
                ],
                'indexWidgetType' => [
                    'asc' => ['attach_gallery_category.indexWidgetType' => SORT_ASC],
                    'desc' => ['attach_gallery_category.indexWidgetType' => SORT_DESC],
                ],
                'enableI18N' => [
                    'asc' => ['attach_gallery_category.enableI18N' => SORT_ASC],
                    'desc' => ['attach_gallery_category.enableI18N' => SORT_DESC],
                ],
                'enablePjax' => [
                    'asc' => ['attach_gallery_category.enablePjax' => SORT_ASC],
                    'desc' => ['attach_gallery_category.enablePjax' => SORT_DESC],
                ],
                'messageCategory' => [
                    'asc' => ['attach_gallery_category.messageCategory' => SORT_ASC],
                    'desc' => ['attach_gallery_category.messageCategory' => SORT_DESC],
                ],
                'formTabs' => [
                    'asc' => ['attach_gallery_category.formTabs' => SORT_ASC],
                    'desc' => ['attach_gallery_category.formTabs' => SORT_DESC],
                ],
                'tabsFieldList' => [
                    'asc' => ['attach_gallery_category.tabsFieldList' => SORT_ASC],
                    'desc' => ['attach_gallery_category.tabsFieldList' => SORT_DESC],
                ],
                'relFiledsDynamic' => [
                    'asc' => ['attach_gallery_category.relFiledsDynamic' => SORT_ASC],
                    'desc' => ['attach_gallery_category.relFiledsDynamic' => SORT_DESC],
                ],
            ]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'default_order' => $this->default_order,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
