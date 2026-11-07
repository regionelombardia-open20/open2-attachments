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
use open20\amos\attachments\models\AttachFileCategorie;
use open20\amos\attachments\models\search\AttachFileCategorieSearch;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
//use open20\amos\core\helpers\T;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use Yii;
use yii\base\InvalidConfigException;
//use yii\filters\VerbFilter;
use yii\helpers\Url;
//use yii\web\NotFoundHttpException;


/**
 * Class AttachFileCategorieController 
 * AttachFileCategorieController implements the CRUD actions for AttachFileCategorie model.
 *
 * @property \open20\amos\attachments\models\AttachFileCategorie $model
 * @property \open20\amos\attachments\models\search\AttachFileCategorieSearch $modelSearch 
 *
 * @package open20\amos\attachments\controllers\base 
 */
class AttachFileCategorieController extends CrudController
{
    /**
     * @var string $layout
     */
    public $layout = 'main';

    public function init()
    {
        $this->setModelObj(new AttachFileCategorie());
        $this->setModelSearch(new AttachFileCategorieSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt').Html::tag('p',
                    BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ],
        ]);

        parent::init();
        $this->setUpLayout();
    }

    /**
     * Lists all AttachFileCategorie models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
        return parent::actionIndex($layout);
    }

    /**
     * Displays a single AttachFileCategorie model.
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
     * Creates a new AttachFileCategorie model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');
        $this->model = new AttachFileCategorie();

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success',
                    BaseAmosModule::t('amoscore', 'Item created'));
//                return $this->redirect(['update', 'id' => $this->model->id]);
                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->addFlash('danger',
                    BaseAmosModule::t('amoscore', 'Item not created, check data'));
            }
        }

        return $this->render('create',
                [
                'model' => $this->model,
                'fid' => NULL,
                'dataField' => NULL,
                'dataEntity' => NULL,
        ]);
    }

    /**
     * Creates a new AttachFileCategorie model by ajax request.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateAjax($fid, $dataField)
    {
        $this->setUpLayout('form');
        $this->model = new AttachFileCategorie();

        if (\Yii::$app->request->isAjax && $this->model->load(Yii::$app->request->post())
            && $this->model->validate()) {
            if ($this->model->save()) {
//Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Item created'));
                return json_encode($this->model->toArray());
            } else {
//Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not created, check data'));
            }
        }

        return $this->renderAjax('_formAjax',
                [
                'model' => $this->model,
                'fid' => $fid,
                'dataField' => $dataField
        ]);
    }

    /**
     * Updates an existing AttachFileCategorie model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');
        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success',
                    BaseAmosModule::t('amoscore', 'Item updated'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger',
                    BaseAmosModule::t('amoscore', 'Item not updated, check data'));
            }
        }

        return $this->render('update',
                [
                'model' => $this->model,
                'fid' => NULL,
                'dataField' => NULL,
                'dataEntity' => NULL,
        ]);
    }

    /**
     * Deletes an existing AttachFileCategorie model.
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
                Yii::$app->getSession()->addFlash('success',
                    BaseAmosModule::t('amoscore',
                        'Element deleted successfully.'));
            } else {
                Yii::$app->getSession()->addFlash('danger',
                    BaseAmosModule::t('amoscore',
                        'You are not authorized to delete this element.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger',
                BaseAmosModule::tHtml('amoscore', 'Element not found.'));
        }
        return $this->redirect(['index']);
    }

    /**
     * @inheritdoc
     * @throws InvalidConfigException If the module has not been configured for
     * categorization
     */
    public function beforeAction($action)
    {
        if ((empty($this->module->categorizationEnabled))
            || (empty($this->module->categorizationModelsEnabled))) {
            throw new InvalidConfigException(FileModule::t('amosattachments',
                'La categorizzazione non è abilitata o configurata'));
        }
        
        if (\Yii::$app->user->isGuest) {
            $titleSection = FileModule::t('amosattachments',
                'Categorie Allegati');
            $urlLinkAll   = '';

            $labelSigninOrSignup = FileModule::t('amosattachments',
                '#beforeActionCtaLoginRegister');
            $titleSigninOrSignup = FileModule::t('amosattachments',
                '#beforeActionCtaLoginRegisterTitle',
                ['platformName' => \Yii::$app->name]
            );
            $labelSignin = FileModule::t('amosattachments',
                '#beforeActionCtaLogin');
            $titleSignin = FileModule::t('amosattachments',
                '#beforeActionCtaLoginTitle',
                ['platformName' => \Yii::$app->name]
            );

            $labelLink = $labelSigninOrSignup;
            $titleLink = $titleSigninOrSignup;
            $socialAuthModule = Yii::$app->getModule('socialauth');
            if ($socialAuthModule && ($socialAuthModule->enableRegister == false)) {
                $labelLink = $labelSignin;
                $titleLink = $titleSignin;
            }

            $ctaLoginRegister = Html::a($labelLink,
                isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon'])
                ? \Yii::$app->params['linkConfigurations']['loginLinkCommon']
                    : \Yii::$app->params['platform']['backendUrl'] . '/' . AmosAdmin::getModuleName() . '/security/login',
                [
                    'title' => $titleLink
                ]);
            $subTitleSection  = Html::tag('p', FileModule::t('amosattachments',
                '#beforeActionSubtitleSectionGuest',
                ['platformName' => \Yii::$app->name, 'ctaLoginRegister' => $ctaLoginRegister]
            ));
        } else {
            $titleSection = FileModule::t('amosattachments',
                'Categorie Allegati');
            $labelLinkAll = null;
            $urlLinkAll = null;
            $titleLinkAll = null;
            if (!empty($this->module->enableDatabankFile)) {
                $labelLinkAll = FileModule::t('amosattachments',
                    'Asset Allegati');
                $urlLinkAll = '/attachments/attach-databank-file';
                $titleLinkAll = FileModule::t('amosattachments',
                    'Visualizza la lista degli allegati nell\'asset');
            }

            $subTitleSection = Html::tag('p', FileModule::t('amosattachments', ''));
        }

        $labelCreate = FileModule::t('amosattachments', 'Nuova');
        $titleCreate = FileModule::t('amosattachments',
            'Crea una nuova categoria');
        $labelManage = FileModule::t('amosattachments', 'Gestisci');
        $titleManage = FileModule::t('amosattachments',
            'Gestisci le categoria');
        $urlCreate   = '/attachments/attach-file-categorie/create';
        $urlManage   = FileModule::t('amosattachments', '#');

        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'news',
            'titleSection' => $titleSection,
            'subTitleSection' => $subTitleSection,
            'urlLinkAll' => $urlLinkAll,
            'labelLinkAll' => $labelLinkAll,
            'titleLinkAll' => $titleLinkAll,
            'labelCreate' => $labelCreate,
            'titleCreate' => $titleCreate,
            'labelManage' => $labelManage,
            'titleManage' => $titleManage,
            'urlCreate' => $urlCreate,
            'urlManage' => $urlManage,
        ];

        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here

        return true;
    }    
}