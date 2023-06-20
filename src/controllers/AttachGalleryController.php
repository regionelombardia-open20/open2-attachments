<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\controllers
 */

namespace open20\amos\attachments\controllers;

use open20\amos\attachments\FileModule;
use open20\amos\attachments\models\AttachGallery;
use open20\amos\attachments\models\AttachGalleryCategory;
use open20\amos\attachments\models\AttachGalleryImage;
use open20\amos\attachments\models\File;
use open20\amos\attachments\models\search\AttachGalleryImageSearch;
use open20\amos\attachments\utility\AttachmentsUtility;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use open20\amos\events\AmosEvents;
use open20\amos\layout\Module;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;

/**
 * Class AttachGalleryController
 * This is the class for controller "AttachGalleryController".
 * @package open20\amos\attachments\controllers
 */
class AttachGalleryController extends \open20\amos\attachments\controllers\base\AttachGalleryController
{
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
                            'load-modal',
                            'single-gallery',
                            'delete-attachments-with-no-file'
                        ],
                        'roles' => ['@']
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
     * @param $galleryId
     * @param $attribute
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionLoadModal($galleryId, $attribute)
    {
        $gallery   = AttachGallery::findOne($galleryId);
        $csrfParam = \Yii::$app->request->csrfParam;
        $csrfToken = \Yii::$app->request->get($csrfParam);

        if ($gallery) {
            $modelSearch =  new AttachGalleryImageSearch();
            $images     = $gallery->attachGalleryImages;
            $categories = AttachGalleryCategory::find()->orderBy('default_order ASC')->all();
            return $this->renderAjax('@vendor/open20/amos-attachments/src/components/views/gallery-view',
                    [
                    'attribute' => $attribute,
                    'images' => $images,
                    'gallery' => $gallery,
                    'categories' => $categories,
                    'csrf' => $csrfToken,
                    'modelSearch' => $modelSearch
            ]);
        }
        return '';
    }

    /**
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionSingleGallery()
    {
        $this->setUpLayout('list');
        $this->setAvailableViews([
        'list' => [
            'name' => 'list',
            'label' => AmosIcons::show('view-list')
                . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'List')),
            'url' => '?currentView=list'
        ],
    ]);
        $this->model = $this->findModel(1);
        $this->setCreateNewBtnLabelSingle();
        $modelSearch = new AttachGalleryImageSearch();
        $this->setModelSearch($modelSearch);

        $this->setListViewsParams(true);
        $dataProviderImages = $modelSearch->searchGenericFiles(\Yii::$app->request->get(), $this->model->id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item updated'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not updated, check data'));
            }
        }

        if (!\Yii::$app->user->isGuest) {
            $this->view->params['titleSection'] = FileModule::t('amosattachments', 'Galleria');
            $this->view->params['labelCreate'] = FileModule::t('amosattachments', 'Carica');
            $this->view->params['titleCreate'] = FileModule::t('amosattachments', 'Inserisci una nuova immagine');
            $this->view->params['urlCreate'] = '/attachments/attach-gallery-image/create?id=' . $this->model->id;


        }

        return $this->render('update', [
            'model' => $this->model,
            'modelSearch' => $modelSearch,
            'dataProviderImages' => $dataProviderImages,
            'currentView' => $this->getCurrentView(),
            'availableViews' => $this->getAvailableViews(),
        ]);
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDeleteAttachmentsWithNoFile(){
        $this->model = $this->findModel(1);
        $this->setCreateNewBtnLabelSingle();
        $modelSearch = new AttachGalleryImageSearch();
        $this->setModelSearch($modelSearch);
        $this->setListViewsParams(true);
        $dataProviderImages = $modelSearch->searchGenericFiles(\Yii::$app->request->get(), $this->model->id);
        $dataProviderImages->pagination = false;
        AttachmentsUtility::deleteAttachmentsWithNoFile($dataProviderImages);
        return $this->redirect('single-gallery');

    }

    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    public function setCreateNewBtnLabelSingle()
    {
        $module = \Yii::$app->getModule('attachments');
        $btnUploadImage = Html::a(FileModule::t('amosattachments', "Carica"), [
                '/attachments/attach-gallery-image/create',
                'id' => 1
            ],
            [
                'class' => 'btn btn-primary',
                'title' => FileModule::t('amosattachments', "Carica nuova immmagine")
            ]
        );

        Yii::$app->view->params['createNewBtnParams']['layout'] = $btnUploadImage;
        $this->buttonRequestImage();


        if(\Yii::$app->user->can('ADMIN')){
            Yii::$app->view->params['additionalButtons']['htmlButtons'] = [
                Html::a(\open20\amos\attachments\FileModule::t('amosattachments',"Ripulisci allegati"), ['/attachments/attach-gallery/delete-attachments-with-no-file'], [
                    'class' => 'btn btn-xs btn-secondary mb-4',
                    'title' => \open20\amos\attachments\FileModule::t('amosattachments',"Ripulisci allegati senza file")
                ])];
        }

    }

    /**
     * @return array
     */
    public static function getManageLinks()
    {
//        $links[] = [
//            'title' => FileModule::t('amosattachments', 'Categorie'),
//            'label' => FileModule::t('amosattachments', 'Tutte le categorie'),
//            'url' => '/attachments/attach-gallery-category/index'
//        ];

        $links[] = [
            'title' => FileModule::t('amosattachments', 'Galleria'),
            'label' => FileModule::t('amosattachments', 'Vedi la galleria'),
            'url' => '/attachments/attach-gallery/single-gallery'
        ];

        return $links;
    }

    /**
     *
     */
    public function buttonRequestImage(){
        $module = \Yii::$app->getModule('attachments');
        if (
            $module->enableRequestImageForGallery == true
            && \Yii::$app->user->can('ATTACHGALLERYREQUEST_CREATE')
        ) {
            $urlSecondAction = '/attachments/attach-gallery-request/create?id=1';
            $labelSecondAction = FileModule::t('amosattachments', "Richiedi nuova");
            $titleSecondAction = FileModule::t('amosattachments', "Richiedi nuova immagine");
            Yii::$app->view->params['urlSecondAction'] = $urlSecondAction;
            Yii::$app->view->params['labelSecondAction'] = $labelSecondAction;
            Yii::$app->view->params['titleSecondAction']= $titleSecondAction;
            Yii::$app->view->params['hideSecondAction']= false;

        }
    }


}
