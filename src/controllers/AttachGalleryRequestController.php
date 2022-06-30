<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\controllers 
 */
 
namespace open20\amos\attachments\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class AttachGalleryRequestController 
 * This is the class for controller "AttachGalleryRequestController".
 * @package open20\amos\attachments\controllers 
 */
class AttachGalleryRequestController extends \open20\amos\attachments\controllers\base\AttachGalleryRequestController
{

    /**
     * @inheritdoc
     */
    public function behaviors() {
        $behaviors = ArrayHelper::merge(
            parent::behaviors(), [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'opened',
                            'closed',
                        ],
                        'roles' => ['ATTACH_IMAGE_REQUEST_OPERATOR','MANAGE_ATTACH_GALLERY','ATTACH_GALLERY_OPERATOR']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'my-requests',
                        ],
                        'roles' => ['MANAGE_ATTACH_GALLERY','ATTACH_GALLERY_OPERATOR']
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post', 'get']
                ]
            ]
        ]);
        return $behaviors;
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionOpened()
    {
        $this->setListViewsParams();
        $this->setCreateNewBtnLabelGeneral();

        $this->setUpLayout('list');
        Url::remember();
        $this->setDataProvider($this->modelSearch->searchOpened(\Yii::$app->request->getQueryParams()));

        return $this->render(
            'index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
            ]
        );
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionClosed(){
        $this->setListViewsParams();
        $this->setCreateNewBtnLabelGeneral();

        $this->setUpLayout('list');
        Url::remember();
        $this->setDataProvider($this->modelSearch->searchClosed(\Yii::$app->request->getQueryParams()));

        return $this->render(
            'index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
            ]
        );
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionMyRequests()
    {
        $this->setListViewsParams();
        $this->setCreateNewBtnLabelGeneral();

        Url::remember();
        $this->setDataProvider($this->modelSearch->searchMyRequests(\Yii::$app->request->getQueryParams()));

        return $this->render(
            'index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
            ]
        );
    }
}
