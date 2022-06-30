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
use open20\amos\attachments\models\AttachGalleryImage;
use open20\amos\attachments\models\base\AttachGallery;
use open20\amos\attachments\models\File;
use open20\amos\attachments\utility\AttachmentsMailUtility;
use open20\amos\attachments\widgets\icons\WidgetIconGalleryDashboard;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use Yii;
use open20\amos\attachments\models\AttachGalleryRequest;
use open20\amos\attachments\models\search\AttachGalleryRequestSearch;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\module\BaseAmosModule;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\core\helpers\T;
use yii\helpers\Url;


/**
 * Class AttachGalleryRequestController
 * AttachGalleryRequestController implements the CRUD actions for AttachGalleryRequest model.
 *
 * @property \open20\amos\attachments\models\AttachGalleryRequest $model
 * @property \open20\amos\attachments\models\search\AttachGalleryRequestSearch $modelSearch
 *
 * @package open20\amos\attachments\controllers\base
 */
class AttachGalleryRequestController extends CrudController
{
    use TabDashboardControllerTrait;


    /**
     * @var string $layout
     */
    public $layout = 'main';

    public function init()
    {
        $this->setModelObj(new AttachGalleryRequest());
        $this->setModelSearch(new AttachGalleryRequestSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ],
            /*'list' => [
                'name' => 'list',
                'label' => AmosIcons::show('view-list') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'List')),         
                'url' => '?currentView=list'
            ],
            'icon' => [
                'name' => 'icon',
                'label' => AmosIcons::show('grid') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Icons')),           
                'url' => '?currentView=icon'
            ],
            'map' => [
                'name' => 'map',
                'label' => AmosIcons::show('map') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Map')),      
                'url' => '?currentView=map'
            ],
            'calendar' => [
                'name' => 'calendar',
                'intestazione' => '', //codice HTML per l'intestazione che verrà caricato prima del calendario,
                                      //per esempio si può inserire una funzione $model->getHtmlIntestazione() creata ad hoc
                'label' => AmosIcons::show('calendar') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Calendari')),                                            
                'url' => '?currentView=calendar'
            ],*/
        ]);

        parent::init();
        $this->setUpLayout();
    }

    /**
     * Lists all AttachGalleryRequest models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();
        $this->setCreateNewBtnLabelGeneral();
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
        return parent::actionIndex($layout);
    }

    /**
     * Displays a single AttachGalleryRequest model.
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
     * @param null $id
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate($id = null)
    {
        $this->setUpLayout('form');
        $this->model = new AttachGalleryRequest();
        $gallery = AttachGallery::findOne($id);
        if ($gallery) {
            $this->model->attach_gallery_id = $id;
        }

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                $this->model->saveCustomTags();
                $this->model->saveImageTags();

                AttachmentsMailUtility::sendEmailRequestImage($this->model);

                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Item created'));
                return $this->redirect(['/attachments/attach-gallery-request/my-requests']);
            } else {
                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not created, check data'));
            }
        }
        //pr($this->model->getErrors());

        return $this->render('create', [
            'model' => $this->model,
            'gallery' => $gallery,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
        ]);
    }

    /**
     * Creates a new AttachGalleryRequest model by ajax request.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateAjax($fid, $dataField)
    {
        $this->setUpLayout('form');
        $this->model = new AttachGalleryRequest();

        if (\Yii::$app->request->isAjax && $this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
//Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Item created'));
                return json_encode($this->model->toArray());
            } else {
//Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not created, check data'));
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
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');
        $this->model = $this->findModel($id);

        if($this->model->status == AttachGalleryRequest::IMAGE_REQUEST_WORKFLOW_STATUS_CLOSED){
            throw new ForbiddenHttpException('Permesso negato');
        }

        $this->model->setScenario(AttachGalleryRequest::SCENARIO_REPLY);
        $this->model->loadCustomTags();
        $this->model->loadTagsImage();

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate() && $this->model->validateFiles()) {
            $this->model->status = AttachGalleryRequest::IMAGE_REQUEST_WORKFLOW_STATUS_CLOSED;
            if ($this->model->save()) {
                $this->model->saveCustomTagsReply();
                $newImage= $this->model->createAttachImageGallery();
                $this->model->attach_gallery_image_id = $newImage->id;
                $this->model->save(false);

                AttachmentsMailUtility::sendEmailRequestImageUploaded($this->model);

                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Item updated'));
                return $this->redirect(['/attachments/attach-gallery-request/closed']);
            } else {
                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not updated, check data'));
            }
        }
//        pr($this->model->getErrors());

        return $this->render('update', [
            'model' => $this->model,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
        ]);
    }

    /**
     * Deletes an existing AttachGalleryRequest model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->model = $this->findModel($id);
        if ($this->model) {
            $this->model->delete();
            if (!$this->model->hasErrors()) {
                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Element deleted successfully.'));
            } else {
                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'You are not authorized to delete this element.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', BaseAmosModule::tHtml('amoscore', 'Element not found.'));
        }
        return $this->redirect(['index']);
    }

    /**
     * This method is useful to set all idea params for all list views.
     */
    protected function setListViewsParams($setCurrentDashboard = true)
    {
        $this->setUpLayout('list');
        if ($setCurrentDashboard) {
            $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
            $this->child_of = WidgetIconGalleryDashboard::className();

        }
    }

    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    public function setCreateNewBtnLabelGeneral($id = 1)
    {

        $btnUploadImage = Html::a(FileModule::t('amosattachments', "Carica nuova"), ['/attachments/attach-gallery-image/create', 'id' => $id], [
            'class' => 'btn btn-primary',
            'title' => FileModule::t('amosattachments', "Carica nuova immmagine")
        ]);
        $btnRequestImage = Html::a(FileModule::t('amosattachments', "Richiedi nuova"), ['/attachments/attach-gallery-request/create', 'id' => $id], [
            'class' => 'btn btn-primary',
            'title' => FileModule::t('amosattachments', "Richiedi nuova immagine")
        ]);

        Yii::$app->view->params['createNewBtnParams']['layout'] = '';
    }
}
