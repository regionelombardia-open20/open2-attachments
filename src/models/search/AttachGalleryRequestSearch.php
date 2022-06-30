<?php

namespace open20\amos\attachments\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\attachments\models\AttachGalleryRequest;

/**
 * AttachGalleryRequestSearch represents the model behind the search form about `open20\amos\attachments\models\AttachGalleryRequest`.
 */
class AttachGalleryRequestSearch extends AttachGalleryRequest
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
            [['title', 'status', 'aspect_ratio', 'text_request', 'text_reply', 'attach_gallery_image_id', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchOpened($params){
        return $this->search($params, 'opened');

    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchClosed($params){
        return $this->search($params, 'closed');

    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchMyRequests($params){
        return $this->search($params, 'my-requests');

    }


    /**
     * @param $params
     * @param string $type
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function search($params, $type = 'all')
    {
        $query = AttachGalleryRequest::find();
        $query->innerJoin('user_profile', 'user_profile.user_id = attach_gallery_request.created_by');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        switch($type){
            case 'opened':
                $query->andWhere(['attach_gallery_request.status' => AttachGalleryRequest::IMAGE_REQUEST_WORKFLOW_STATUS_OPENED]);
                break;
            case 'closed':
                $query->andWhere(['attach_gallery_request.status' => AttachGalleryRequest::IMAGE_REQUEST_WORKFLOW_STATUS_CLOSED]);
                break;
            case 'my-requests':
                $query->andWhere(['attach_gallery_request.created_by' => \Yii::$app->user->id]);
                break;
        }


        $dataProvider->setSort([
            'attributes' => [
                'title' => [
                    'asc' => ['attach_gallery_request.title' => SORT_ASC],
                    'desc' => ['attach_gallery_request.title' => SORT_DESC],
                ],
                'aspect_ratio' => [
                    'asc' => ['attach_gallery_request.aspect_ratio' => SORT_ASC],
                    'desc' => ['attach_gallery_request.aspect_ratio' => SORT_DESC],
                ],
                'text_request' => [
                    'asc' => ['attach_gallery_request.text_request' => SORT_ASC],
                    'desc' => ['attach_gallery_request.text_request' => SORT_DESC],
                ],
                'text_reply' => [
                    'asc' => ['attach_gallery_request.text_reply' => SORT_ASC],
                    'desc' => ['attach_gallery_request.text_reply' => SORT_DESC],
                ],
                'created_at' => [
                    'asc' => ['attach_gallery_request.created_at' => SORT_ASC],
                    'desc' => ['attach_gallery_request.created_at' => SORT_DESC],
                ],
                'created_by' => [
                    'asc' => ['user_profile.nome' => SORT_ASC],
                    'desc' => ['user_profile.nome' => SORT_DESC],
                ],
                'workflowStatus.label' => [
                    'asc' => ['attach_gallery_request.status' => SORT_ASC],
                    'desc' => ['attach_gallery_request.status' => SORT_DESC],
                ],
                'id' => [
                    'asc' => ['attach_gallery_request.id' => SORT_ASC],
                    'desc' => ['attach_gallery_request.id' => SORT_DESC],
                ],
            ]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'attach_gallery_request.created_at' => $this->created_at,
            'attach_gallery_request.updated_at' => $this->updated_at,
            'attach_gallery_request.deleted_at' => $this->deleted_at,
            'attach_gallery_request.created_by' => $this->created_by,
            'attach_gallery_request.updated_by' => $this->updated_by,
            'attach_gallery_request.deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'attach_gallery_request.title', $this->title])
            ->andFilterWhere(['like', 'attach_gallery_request.status', $this->status])
            ->andFilterWhere(['like', 'attach_gallery_request.aspect_ratio', $this->aspect_ratio])
            ->andFilterWhere(['like', 'attach_gallery_request.text_request', $this->text_request])
            ->andFilterWhere(['like', 'attach_gallery_request.text_reply', $this->text_reply])
            ->andFilterWhere(['like', 'attach_gallery_image_id', $this->attach_gallery_image_id]);

        return $dataProvider;
    }
}
