<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\attachments\controllers 
 */
 
namespace lispa\amos\attachments\controllers;
use lispa\amos\attachments\models\AttachGallery;
use lispa\amos\attachments\models\AttachGalleryCategory;
use lispa\amos\attachments\models\AttachGalleryImage;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;


/**
 * Class AttachGalleryImageController 
 * This is the class for controller "AttachGalleryImageController".
 * @package lispa\amos\attachments\controllers 
 */
class AttachGalleryImageController extends \lispa\amos\attachments\controllers\base\AttachGalleryImageController
{
    /**
     * @inheritdoc
     */
    public function behaviors() {
        $behaviors = ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'upload-from-gallery-ajax',
                            'delete-from-session-ajax',
                            'load-modal'
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
    public function actionUploadFromGalleryAjax($id, $attribute, $csrf){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $image = AttachGalleryImage::findOne($id);
        if($image) {
            $data = ['id' => $id, 'attribute' => $attribute];
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
    public function actionDeleteFromSessionAjax($csrf){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        \Yii::$app->session->remove($csrf);

        return ['success' => false];
    }

    /**
     * @param $id
     * @param $csrf
     * @return array
     */
    public function actionLoadModal($galleryId, $attribute){
        $gallery = AttachGallery::findOne($galleryId);
        if($gallery) {
            $images = $gallery->attachGalleryImages;
            $categories = AttachGalleryCategory::find()->all();
            return $this->renderAjax('@vendor/lispa/amos-attachments/src/components/views/gallery-view', [
                'attribute' => $attribute,
                'images' => $images,
                'gallery' => $gallery,
                'categories' => $categories,
            ]);
        }

    }
}
