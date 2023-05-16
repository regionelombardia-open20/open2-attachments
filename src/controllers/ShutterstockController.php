<?php

namespace open20\amos\attachments\controllers;

use open20\amos\admin\AmosAdmin;
use open20\amos\attachments\FileModule;
use open20\amos\attachments\models\AttachDatabankFile;
use open20\amos\attachments\models\AttachGallery;
use open20\amos\attachments\models\AttachGalleryImage;
use open20\amos\attachments\models\search\AttachGallerySearch;
use open20\amos\attachments\models\Shutterstock;
use open20\amos\attachments\utility\ShutterstockClient;
use open20\amos\core\controllers\BackendController;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\response\Response;
use yii\base\ErrorException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class ShutterstockController extends CrudController
{

    /**
     * @var string $layout
     */
    public $layout = 'main';

    /**
     *
     */
    public function init()
    {
        $thisFileModule = FileModule::getInstance();

        $this->setModelObj(new AttachGallery());
        $this->setModelSearch(new AttachGallerySearch());

        $this->setAvailableViews([
//            'icon' => [
//                'name' => 'icon',
//                'label' => AmosIcons::show('grid') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Icons')),
//                'url' => '?currentView=icon'
//            ],
            'list' => [
                'name' => 'list',
                'label' => AmosIcons::show('view-list') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'List')),
                'url' => '?currentView=list'
            ],
//            'grid' => [
//                'name' => 'grid',
//                'label' => AmosIcons::show('view-list-alt')
//                    . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
//                'url' => '?currentView=grid'
//            ],
        ]);

        parent::init();
        $this->setUpLayout();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(
            parent::behaviors(), [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'auth',
                            'load-modal',
                            'search-shutterstock-ajax',
                            'load-detail',
                            'load-detail-index',
                            'upload-from-gallery-ajax',
                            'suggestions-image-ajax',
                            'copy-luya-in-backend'
                        ],
                        'roles' => ['MANAGE_ATTACH_SHUTTERSTOCK']
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
     *
     * @param $action
     * @return boolean
     */
    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            $titleSection = FileModule::t('amosattachments', 'Cerca immagini Shutterstock');

        } else {
            $titleSection = FileModule::t('amosattachments', 'Cerca immagini Shutterstock');
        }

        $this->view->params = [
            'titleSection' => $titleSection,
            'hideCreate' => true,
            'labelLinkAll' => FileModule::t('amosattachment', 'Tutte le immagini'),
            'urlLinkAll' => '/attachments/attach-gallery/single-gallery',
            'titleLinkAll' => FileModule::t('amosattachment', 'Visualizza tutte le immagini')
        ];

        if (!parent::beforeAction($action)) {
            return false;
        }
        // other custom code here
        return true;
    }

    /**
     * @return void
     */
    public function actionAuth()
    {
        if (!empty($_GET)) {
            pr($_GET);
//            pr($_GET['state']);
            die;
        }
        $shutterstockClient = new ShutterstockClient();
        $resp = $shutterstockClient->authorize();
    }


    public function actionIndex($layout = null)
    {
        $shutterstockClient = new ShutterstockClient();
//        $sub = $shutterstockClient->getSubscriptions();
//        Shutterstock::getSubscriptionId();
//        pr(Shutterstock::getSubscriptionDownloadRemaining());
//        $a = $shutterstockClient->getSuggestionsImage(['query' => 'sp']);
//        pr($a);
//        $categories = $shutterstockClient->searchImageCategories();
//        $id = Shutterstock::getSubscriptionId();
//        $sub = $shutterstockClient->getSubscriptions();
//        $proscription = $shutterstockClient->getProscriptions();
//pr($id);
//        pr($sub);
//pr($proscription,'FFF');
//        $a= [
//                 "images" => [
//                     [
//                         "size" => 'huge',
//                         "image_id" => "1958073748",
//                         "subscription_id" => "7032c15d70f34876aa7789364f465af0",
//                     ]
//                 ]
//             ];
//        $proscription = $shutterstockClient->buyProscriptions($a);
//        $down = $shutterstockClient->downloadImage('f4052e15-0e28-4899-8ce5-f00298412e0d');
//        pr($shutterstockClient->errorMessage, 'error');

//        $a = $shutterstockClient->getProscriptions(['image_id' => '1458224861']);
//        if (!empty($a->data)) {
//            pr($a->data[0]);
//            pr("c'è");
//        }
//        pr($a);

        $this->setUpLayout('list');
        $modelSearch = new Shutterstock();
        $dataProvider = $modelSearch->search(\Yii::$app->request->get());

//                $shutterstockClient = new ShutterstockClient();
//                $resp = $shutterstockClient->getSubscriptions();
//                pr('----------');
//                pr($resp);


        return $this->render('index', [
            'modelSearch' => $modelSearch,
            'dataProvider' => $dataProvider,
            'currentView' => $this->getCurrentView()
        ]);
    }

    /**
     * @param $galleryId
     * @param $attribute
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionLoadModal($attribute)
    {
        $csrfParam = \Yii::$app->request->csrfParam;
        $csrfToken = \Yii::$app->request->get($csrfParam);
        $modelSearch = new Shutterstock();
        $dataProvider = $modelSearch->search(\Yii::$app->request->get());

        return $this->renderAjax('@vendor/open20/amos-attachments/src/components/views/shutterstock/shutterstock-view',
            [
                'attribute' => $attribute,
                'modelSearch' => $modelSearch,
                'dataProvider' => $dataProvider,
                'csrf' => $csrfToken,
                'errorMessage' => $modelSearch->errorMessage,
                'firstLoad' => true
            ]);
    }


    /**
     * @return string
     */
    public function actionSearchShutterstockAjax()
    {
        $template = '@vendor/open20/amos-attachments/src/components/views/shutterstock/shutterstock_items';
        $attribute = \Yii::$app->request->get('attribute');
        $modelSearch = new Shutterstock();
        $dataProvider = $modelSearch->search(\Yii::$app->request->get());

        return $this->renderAjax($template, [
            'dataProvider' => $dataProvider,
            'attribute' => $attribute,
            'modelSearch' => $modelSearch,
            'errorMessage' => $modelSearch->errorMessage
        ]);
    }

    /**
     * @param $idImage
     * @param $galleryId
     * @param $attribute
     * @return string
     */
    public function actionLoadDetail($id_image, $attribute)
    {
        $template = '@vendor/open20/amos-attachments/src/components/views/shutterstock/_detail_modal';
        $shutterstock = new ShutterstockClient();
        $model = $shutterstock->searchImageDetail($id_image, ['view' => 'full']);
        $contributorName = Shutterstock::contributorName($model);

        return $this->renderAjax($template, [
            'model' => $model,
            'attribute' => $attribute,
            'contributorName' => $contributorName
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
        $template = '@vendor/open20/amos-attachments/src/views/shutterstock/_detail_modal';
        $shutterstock = new ShutterstockClient();
        $model = $shutterstock->searchImageDetail($id_image, ['view' => 'full']);
        $contributorName = Shutterstock::contributorName($model);
        if ($model) {
            return $this->renderAjax($template, [
                'model' => $model,
                'contributorName' => $contributorName
            ]);
        }
        return '';
    }

    /**
     * @param $id
     * @param $csrf
     * @return array
     */
    public function actionUploadFromGalleryAjax($id, $attribute = null, $proscription_type = null, $name = null, $csrf = null)
    {
        try {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            //se l'immagine esisite già mando ritorno l'immagine del databank immagini
            $attachGalleryImage = AttachGalleryImage::find()->andWhere(['shutterstock_image_id' => $id])->one();
            if ($attachGalleryImage) {
                $data = \Yii::$app->session->get($csrf);
                $data [$attribute] = ['id' => $attachGalleryImage->attachImage->id, 'attribute' => $attribute, 'type' => AttachGalleryImage::className()];
                \Yii::$app->session->set($csrf, $data);
                return [
                    'success' => true,
                    'gallery_image_id' => $attachGalleryImage->id,
                    'urlDownload' => $attachGalleryImage->attachImage->getWebUrl(),
                    'message' => FileModule::t('amosattachments', "Salvataggio completato")
                ];
            }
            // se non c'è nel databank la richiedo la lincexa a shutterstock
            /** @var  $module FileModule */
            $module = \Yii::$app->getModule('attachments');
            $subscriptionId = Shutterstock::getSubscriptionId();
            $shutterstock = new ShutterstockClient();

            //if is bought the proscription don't buy it again
            $isBought = false;
            $proscriptions = $shutterstock->getProscriptions(['image_id' => $id]);
            $msg = $shutterstock->errorMessage;
            $activeProscription = Shutterstock::activeProscriptionForImage($proscriptions, $proscription_type);

            if (empty($activeProscription)) {
                $isBought = true;
                $params = [
                    "images" => [
                        [
                            "size" => $proscription_type,
                            "image_id" => $id,
                            "subscription_id" => $subscriptionId,
                        ]
                    ]
                ];
                $proscriptions = $shutterstock->buyProscriptions($params);
                if (!empty($proscriptions->data)) {
                    $activeProscription = $proscriptions->data[0];
                    Shutterstock::getSubscriptionId(); // salva i dati delle immagini scaricabili rimanenti
                }
            }
            //use proscription to get the download url and save file on databank gallery
            $attachGalleryImage = Shutterstock::saveAttachmentFromShutterstock($id, $attribute, $activeProscription, $isBought, $name, $module, $csrf);
            if (!empty($attachGalleryImage['error'])) {
                return [
                    'success' => false,
                    'message' =>  $attachGalleryImage['error'] ?: $msg,
                ];
            } else if (!empty($attachGalleryImage)) {
                $data = \Yii::$app->session->get($csrf);
                $data [$attribute] = ['id' => $attachGalleryImage->attachImage->id, 'attribute' => $attribute, 'type' => AttachGalleryImage::className()];
                $msg = $attachGalleryImage->errorMessage;
                \Yii::$app->session->set($csrf, $data);
                return [
                    'success' => true,
                    'gallery_image_id' => $attachGalleryImage->id,
                    'urlDownload' => $attachGalleryImage->attachImage->getWebUrl(),
                    'message' => $msg
                ];
            }
            return [
                'success' => false,
                'message' => $msg
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'dataError' => $e->getTraceAsString()];

        }
    }

    /**
     * @param $query
     * @return array|mixed
     */
    public function actionSuggestionsImageAjax($query)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $shutterstock = new ShutterstockClient();
        $suggestions = $shutterstock->getSuggestionsImage(['query' => $query, 'limit' => 10]);
        return $suggestions;
    }



}