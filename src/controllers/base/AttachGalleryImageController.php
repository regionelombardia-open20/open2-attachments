<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\controllers\base
 */

namespace open20\amos\attachments\controllers\base;

use open20\amos\attachments\FileModule;
use open20\amos\attachments\models\AttachGallery;
use open20\amos\attachments\models\AttachGalleryImage;
use open20\amos\attachments\models\search\AttachGalleryImageSearch;
use open20\amos\attachments\utility\AttachmentsUtility;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\core\helpers\T;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * Class AttachGalleryImageController
 * AttachGalleryImageController implements the CRUD actions for AttachGalleryImage model.
 *
 * @property \open20\amos\attachments\models\AttachGalleryImage $model
 * @property \open20\amos\attachments\models\search\AttachGalleryImageSearch $modelSearch
 *
 * @package open20\amos\attachments\controllers\base
 */
class AttachGalleryImageController extends CrudController
{
    /**
     * @var string $layout
     */
    public $layout = 'main';
    
    /**
     * 
     * @var type
     */
    public $module;
    
    /**
     * 
     */
    public function init()
    {
        $this->setModelObj(new AttachGalleryImage());
        $this->setModelSearch(new AttachGalleryImageSearch());
        $this->module = \Yii::$app->getModule('attachments');

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ],
        ]);

        parent::init();
        $this->setUpLayout();
    }

    /**
     * Lists all AttachGalleryImage models.
     * @return mixed
     */
    public function actionIndex($layout = null)
    {
        if ($this->module->enableSingleGallery) {
            return $this->redirect(['/attachments/attach-gallery/single-gallery']);
        }

        Url::remember();
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
        return parent::actionIndex($layout);
    }

    /**
     * Displays a single AttachGalleryImage model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->model = $this->findModel($id);
        if ($this->model->load(Yii::$app->request->post()) && $this->model->save()) {
            return $this->redirect(['view', 'id' => $this->model->id]);
        } else {
            return $this->render('view', ['model' => $this->model]);
        }
    }

    /**
     * Creates a new AttachGalleryImage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $this->setUpLayout('form');
        $this->model = new AttachGalleryImage();
        
        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate() && $this->model->validateFiles()) {
            if(AttachGallery::findOne($id)) {
                $this->model->gallery_id = $id;
                if ($this->model->save()) {
                    $this->model->saveCustomTags();
                    $this->model->saveImageTags();
                    $this->model->saveOnLuya();
                    Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item created'));
                    if ($this->module->enableSingleGallery){
                        return $this->redirect(['/attachments/attach-gallery/single-gallery']);
                    }
                    
                    return $this->redirect(['/attachments/attach-gallery/update', 'id' => $this->model->gallery_id]);
                } else {
                    Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not created, check data'));
                }
            }
        }

        return $this->render('create', [
            'model' => $this->model,
            'fid' => null,
            'dataField' => null,
            'dataEntity' => null,
        ]);
    }

    /**
     * Creates a new AttachGalleryImage model by ajax request.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateAjax($fid, $dataField)
    {
        $this->setUpLayout('form');
        $this->model = new AttachGalleryImage();

        if (\Yii::$app->request->isAjax && $this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                return json_encode($this->model->toArray());
            }
        }

        return $this->renderAjax('_formAjax', [
            'model' => $this->model,
            'fid' => $fid,
            'dataField' => $dataField
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');

        $this->model = $this->findModel($id);
        $this->model->loadCustomTags();
        $this->model->loadTagsImage();

        $this->setCreateNewBtnLabelGeneral($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate() && $this->model->validateFiles()) {
            if ($this->model->save()) {
                $this->model->saveCustomTags();
                $this->model->saveImageTags();

                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item updated'));
                if ($this->module->enableSingleGallery) {
                    return $this->redirect(['/attachments/attach-gallery/single-gallery']);
                }

                return $this->redirect(['/attachments/attach-gallery/update', 'id' => $this->model->gallery_id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not updated, check data'));
            }
        }

        return $this->render('update', [
            'model' => $this->model,
            'fid' => null,
            'dataField' => null,
            'dataEntity' => null,
        ]);
    }

    /**
     * Deletes an existing AttachGalleryImage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->model = $this->findModel($id);
        $module = \Yii::$app->getModule('attachments');

        if ($this->model) {
            if($module->showLuyaGallery) {
                if (!empty($this->model->attachImage)) {
                    $storage = $this->model->attachImage->getAdminStorageFile();
                    if($storage){
                        $storage->deleteCms();
                    }
                }
            }
            if($this->model->attachImage){
                $this->model->attachImage->delete();
            }
            $this->model->delete();
            if (!$this->model->hasErrors()) {
                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Element deleted successfully.'));
            } else {
                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'You are not authorized to delete this element.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', BaseAmosModule::tHtml('amoscore', 'Element not found.'));
        }
        
        if ($this->module->enableSingleGallery) {
            return $this->redirect(['/attachments/attach-gallery/single-gallery']);
        }
        
        return $this->redirect(['/attachments/attach-gallery/update', 'id' => $this->model->gallery_id]);
    }

    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    private function setCreateNewBtnLabelGeneral($id)
    {
        $btnUploadImage = Html::a(
            FileModule::t('amosattachments', 'Carica'),
            [
                '/attachments/attach-gallery-image/create',
                'id' => $id
            ],
            [
                'class' => 'btn btn-primary',
                    'title' => FileModule::t('amosattachments', "Carica nuova immmagine")
            ]
        );
        
        $btnRequestImage = Html::a(
            FileModule::t('amosattachments', "Richiedi nuova"),
            [
                '/attachments/attach-gallery-request/create',
                'id' => $id
            ],
            [
                'class' => 'btn btn-primary',
                'title' => FileModule::t('amosattachments', "Richiedi nuova immagine")
            ]
        );

        Yii::$app->view->params['createNewBtnParams']['layout'] = $btnUploadImage . $btnRequestImage;
    }

    /**
     * @param $attach_file_id
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionDeleteCmsFile($attach_file_id){
        $isDeleted = AttachmentsUtility::deleteCmsFile($attach_file_id);
        if ($isDeleted) {
            Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Element deleted successfully.'));
        } else {
            Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'You are not authorized to delete this element.'));
        }
        return $this->redirect('/attachments/attach-gallery/index');
    }

}
