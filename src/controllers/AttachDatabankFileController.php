<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\controllers
 */

namespace open20\amos\attachments\controllers;

use open20\amos\admin\AmosAdmin;
use open20\amos\attachments\FileModule;
use open20\amos\attachments\models\AttachDatabankFile;
use open20\amos\attachments\models\AttachGalleryImage;
use open20\amos\attachments\models\AttachGenericFile;
use open20\amos\attachments\models\File;
use open20\amos\attachments\models\search\AttachDatabankFileSearch;
use open20\amos\core\helpers\Html;
use open20\amos\tag\models\Tag;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * Class AttachDatabankFileController
 * This is the class for controller "AttachDatabankFileController".
 * @package open20\amos\attachments\controllers
 */
class AttachDatabankFileController extends \open20\amos\attachments\controllers\base\AttachDatabankFileController
{


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
                            'get-autocomplete-tag',
                            'load-detail',
                            'load-detail-index',
                            'load-modal',
                            'upload-from-databank-file-ajax',
                            'search-databank-file-ajax',
                            'convert-file',
                            'delete-cms-file'
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
     *
     * @param $action
     * @return boolean
     */
    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            $titleSection = FileModule::t('amosattachments', 'Gestione allegati');
            $ctaLoginRegister = Html::a(
                FileModule::t('amosattachments', 'accedi o registrati alla piattaforma'),
                isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon'])
                    ? \Yii::$app->params['linkConfigurations']['loginLinkCommon']
                    : \Yii::$app->params['platform']['backendUrl']
                    . '/'
                    . AmosAdmin::getModuleName()
                    . '/security/login',
                [
                    'title' => FileModule::t('amosattachments',
                        'Clicca per accedere o registrarti alla piattaforma {platformName}',
                        ['platformName' => \Yii::$app->name])
                ]
            );
        } else {
            $titleSection = FileModule::t('amosattachments', 'Asset Allegati');
        }

        $labelCreate = FileModule::t('amosattachments', 'Carica');
        $titleCreate = FileModule::t('amosattachments', 'Carica un nuovo allegato');
        $labelManage = FileModule::t('amosattachments', 'Gestisci');
        $titleManage = FileModule::t('amosattachments', 'Gestisci gli allegati');
        $urlCreate = '/attachments/attach-databank-file/create';

        $this->view->params = [
            'titleSection' => $titleSection,
            'labelCreate' => $labelCreate,
            'titleCreate' => $titleCreate,
            'labelManage' => $labelManage,
            'titleManage' => $titleManage,
            'urlCreate' => $urlCreate
        ];

        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here
        return true;
    }

    /**
     * @param $str
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionGetAutocompleteTag($term)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $tags = [];
        $root = Tag::find()->andWhere(['codice' => AttachDatabankFile::ROOT_TAG_DATABANK_FILE])->one();
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
    public function actionLoadDetail($id_file, $attribute)
    {
        $template = '@vendor/open20/amos-attachments/src/components/views/databank_file/_detail_file_modal';
        $model = AttachGenericFile::findOne($id_file);
        if ($model) {
            return $this->renderAjax($template, [
                'model' => $model,
                'attribute' => $attribute,
            ]);
        }

        return '';
    }

    /**
     * @param $idImage
     * @param $galleryId
     * @param $attribute
     * @return string
     */
    public function actionLoadDetailIndex($id_file)
    {
        $template = '@vendor/open20/amos-attachments/src/views/attach-databank-file/_detail_file_modal';
        $model = AttachGenericFile::findOne($id_file);
        if ($model) {
            return $this->renderAjax($template, [
                'model' => $model,
            ]);
        }
        return '';
    }

    /**
     * @param $galleryId
     * @param $attribute
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionLoadModal($attribute, $pageSize)
    {
        $csrfParam = \Yii::$app->request->csrfParam;
        $csrfToken = \Yii::$app->request->get($csrfParam);
            return $this->renderAjax('@vendor/open20/amos-attachments/src/components/views/databank_file/databank-file-view',
                [
                    'attribute' => $attribute,
                    'pageSize' => $pageSize,
                    'csrf' => $csrfToken,
                ]);
    }

    /**
     * @param $id
     * @param $csrf
     * @return array
     */
    public function actionUploadFromDatabankFileAjax($file_ids, $attribute, $csrf)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $ids = explode(',',$file_ids);

        $data = \Yii::$app->session->get($csrf);
        $data [$attribute] = ['ids' => $ids, 'attribute' => $attribute, 'type' => AttachDatabankFile::className()];
        \Yii::$app->session->set($csrf, $data);
        return ['success' => true];
//        return ['success' => false];
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
     * @return string
     */
    public function actionSearchDatabankFileAjax()
    {
        $template = '@vendor/open20/amos-attachments/src/components/views/databank_file/items_databank_files';
        $model = new AttachDatabankFileSearch();
        $attribute = \Yii::$app->request->get('attribute');
        $file_ids = \Yii::$app->request->get('file_ids');
        $dataProvider = $model->searchGenericFiles(\Yii::$app->request->get());

        return $this->renderAjax($template, [
            'dataProvider' => $dataProvider,
            'attribute' => $attribute,
            'file_ids' => $file_ids
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws ForbiddenHttpException
     */
    public function actionConvertFile($id){
        $file = File::findOne($id);
        $databankFile = $file->saveInBackendFromLuya(AttachDatabankFile::className());
        if(!empty($databankFile)){
            return $this->redirect(['/attachments/attach-databank-file/update','id' => $databankFile->id]);
        }
        throw new ForbiddenHttpException();
    }
}
