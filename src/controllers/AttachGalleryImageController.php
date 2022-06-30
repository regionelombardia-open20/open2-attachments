<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\controllers
 */

namespace open20\amos\attachments\controllers;

use open20\amos\attachments\models\AttachGallery;
use open20\amos\attachments\models\AttachGalleryCategory;
use open20\amos\attachments\models\AttachGalleryImage;
use open20\amos\attachments\models\search\AttachGalleryImageSearch;
use open20\amos\tag\models\Tag;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Response;


/**
 * Class AttachGalleryImageController
 * This is the class for controller "AttachGalleryImageController".
 * @package open20\amos\attachments\controllers
 */
class AttachGalleryImageController extends \open20\amos\attachments\controllers\base\AttachGalleryImageController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'upload-from-gallery-ajax',
                            'delete-from-session-ajax',
                            'load-modal',
                            'get-autocomplete-tag',
                            'load-detail',
                            'search-gallery-ajax',
                            'load-detail-index'
                        ],
                        'roles' => ['@']
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get']
                ]
            ]
        ]);
        return $behaviors;
    }

    /**
     * @param $id
     * @param $csrf
     * @return array
     */
    public function actionUploadFromGalleryAjax($id, $attribute, $csrf)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $image = AttachGalleryImage::findOne($id);
        if ($image) {
            $data = \Yii::$app->session->get($csrf);
            $data [$attribute] = ['id' => $id, 'attribute' => $attribute];
            \Yii::$app->session->set($csrf, $data);
            return ['success' => true];
        }
        return ['success' => false];
    }

    /**
     * @param $id
     * @param $csrf
     * @return array
     */
    public function actionDeleteFromSessionAjax($csrf)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        \Yii::$app->session->remove($csrf);

        return ['success' => false];
    }

    /**
     * @param $id
     * @param $csrf
     * @return array
     */
    public function actionLoadModal($galleryId, $attribute)
    {
        $gallery = AttachGallery::findOne($galleryId);
        if ($gallery) {
            $images = $gallery->attachGalleryImages;
            $categories = AttachGalleryCategory::find()->all();
            return $this->renderPartial('@vendor/open20/amos-attachments/src/components/views/gallery-view', [
                'attribute' => $attribute,
                'images' => $images,
                'gallery' => $gallery,
                'categories' => $categories,
            ]);
        }
    }

    /**
     * @param $str
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionGetAutocompleteTag($term)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $tags = [];
        $root = Tag::find()->andWhere(['codice' => AttachGalleryImage::ROOT_TAG_CUSTOM])->one();
        if ($root && !empty($term)) {
            $tagsModels = Tag::find()
                ->andWhere(['root' => $root->id])
                ->andWhere(['LIKE', 'nome', $term])
                ->andWhere(['!=', 'id', $root->id])
                ->all();
        }
        foreach ($tagsModels as $tag) {
            $tags [] = ['label' => $tag->nome];
        }
        return $tags;
    }

    /**
     * @param $idImage
     * @param $galleryId
     * @param $attribute
     * @return string
     */
    public function actionLoadDetail($id_image, $attribute)
    {
        $model = AttachGalleryImage::findOne($id_image);
        if ($model) {

            return $this->renderAjax('@vendor/open20/amos-attachments/src/components/views/_detail_image_modal', [
                'model' => $model,
                'attribute' => $attribute,
            ]);
        }
        return '';
    }

    /**
     * @return string
     */
    public function actionSearchGalleryAjax()
    {
        $model = new AttachGalleryImageSearch();
        $attribute = \Yii::$app->request->get('attribute');
        $dataProvider = $model->search(\Yii::$app->request->get());

        return $this->renderAjax('@vendor/open20/amos-attachments/src/components/views/images_gallery', [
            'dataProvider' => $dataProvider,
            'attribute' => $attribute
        ]);
    }

    /**
     * @param $idImage
     * @param $galleryId
     * @param $attribute
     * @return string
     */
    public function actionLoadDetailIndex($id_image)
    {
        $model = AttachGalleryImage::findOne($id_image);
        if ($model) {

            return $this->renderAjax('@vendor/open20/amos-attachments/src/views/attach-gallery-image/_detail_image_modal', [
                'model' => $model,
            ]);
        }
        return '';
    }
}
