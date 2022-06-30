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
use Yii;
use open20\amos\attachments\models\AttachGalleryImage;
use open20\amos\attachments\models\search\AttachGalleryImageSearch;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\module\BaseAmosModule;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\core\helpers\T;
use yii\helpers\Url;


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

    public function init()
    {
        $this->setModelObj(new AttachGalleryImage());
        $this->setModelSearch(new AttachGalleryImageSearch());

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
     * Lists all AttachGalleryImage models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
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
        $module = \Yii::$app->getModule('attachments');

        $this->setUpLayout('form');
        $this->model = new AttachGalleryImage();
        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate() && $this->model->validateFiles()) {
            if(AttachGallery::findOne($id)) {
                $this->model->gallery_id = $id;
                if ($this->model->save()) {
                    $this->model->saveCustomTags();
                    $this->model->saveImageTags();

                    Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item created'));
                    if($module->enableSingleGallery){
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
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
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
//Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item created'));
                return json_encode($this->model->toArray());
            } else {
//Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not created, check data'));
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
        $module = \Yii::$app->getModule('attachments');

        $this->model = $this->findModel($id);
        $this->model->loadCustomTags();
        $this->model->loadTagsImage();

        $this->setCreateNewBtnLabelGeneral($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate() && $this->model->validateFiles()) {
            if ($this->model->save()) {
                $this->model->saveCustomTags();
                $this->model->saveImageTags();

                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item updated'));
                if($module->enableSingleGallery){
                    return $this->redirect(['/attachments/attach-gallery/single-gallery']);
                }
                return $this->redirect(['/attachments/attach-gallery/update', 'id' => $this->model->gallery_id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not updated, check data'));
            }
        }

        return $this->render('update', [
            'model' => $this->model,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
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
        $module = \Yii::$app->getModule('attachments');

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
        if($module->enableSingleGallery){
            return $this->redirect(['/attachments/attach-gallery/single-gallery']);
        }
        return $this->redirect(['/attachments/attach-gallery/update', 'id' => $this->model->gallery_id]);
    }

    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    private function setCreateNewBtnLabelGeneral($id)
    {

        $btnUploadImage = Html::a(FileModule::t('amosattachments', "Carica nuova"), ['/attachments/attach-gallery-image/create', 'id' => $id], [
            'class' => 'btn btn-primary',
            'title' => FileModule::t('amosattachments', "Carica nuova immmagine")
        ]);
        $btnRequestImage = Html::a(FileModule::t('amosattachments', "Richiedi nuova"), ['/attachments/attach-gallery-request/create', 'id' => $id], [
            'class' => 'btn btn-primary',
            'title' => FileModule::t('amosattachments', "Richiedi nuova immagine")
        ]);

        Yii::$app->view->params['createNewBtnParams']['layout'] = $btnUploadImage.$btnRequestImage;
    }
}
