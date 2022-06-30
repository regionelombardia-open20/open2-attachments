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
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use open20\amos\layout\Module;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use open20\amos\attachments\FileModule;
use Yii;

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
    public function behaviors() {
        $behaviors = ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'load-modal',
                            'single-gallery'
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
     * @param $galleryId
     * @param $attribute
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionLoadModal($galleryId, $attribute){
        $gallery = AttachGallery::findOne($galleryId);
        if($gallery) {
            $images = $gallery->attachGalleryImages;
            $categories = AttachGalleryCategory::find()->orderBy('default_order ASC')->all();
            return $this->renderAjax('@vendor/open20/amos-attachments/src/components/views/gallery-view', [
                'attribute' => $attribute,
                'images' => $images,
                'gallery' => $gallery,
                'categories' => $categories,
            ]);
        }
        return '';
    }

    /**
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionSingleGallery(){

        $this->setUpLayout('form');
        $this->model = $this->findModel(1);
        $this->setCreateNewBtnLabelSingle();

        $this->setListViewsParams($setCurrentDashboard = true);
        $dataProviderImages = new ActiveDataProvider([
            'query' => $this->model->getAttachGalleryImages()
        ]);

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
            $this->view->params['labelCreate']  = FileModule::t('amosattachments', 'Nuova immagine');
            $this->view->params['titleCreate'] = FileModule::t('amosattachments', 'Inserisci una nuova immagine');
            $this->view->params['urlCreate']   = '/attachments/attach-gallery-image/create?id='. $this->model->id;
            
           

        }

        return $this->render('update', [
            'model' => $this->model,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
            'dataProviderImages' => $dataProviderImages
        ]);
    }

    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    private function setCreateNewBtnLabelSingle()
    {
     
        Yii::$app->view->params['createNewBtnParams']['layout'] = '';
    
    }

     /**
     * @return array
     */
    public static function getManageLinks()
    {
 
        $links[] = [
            'title' => FileModule::t('amosattachments', 'Categorie'),
            'label' => FileModule::t('amosattachments', 'Tutte le categorie'),
            'url' => '/attachments/attach-gallery-category/index'
        ];
        $links[] = [
            'title' => FileModule::t('amosattachments', 'Galleria'),
            'label' => FileModule::t('amosattachments', 'Vedi la galleria'),
            'url' => '/attachments/attach-gallery/single-gallery'
        ];

      
        

        return $links;
    }

    
}
