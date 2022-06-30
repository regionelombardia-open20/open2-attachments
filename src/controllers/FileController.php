<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\controllers
 * @category   CategoryName
 */

namespace open20\amos\attachments\controllers;

use open20\amos\attachments\FileModule;
use open20\amos\attachments\FileModuleTrait;
use open20\amos\attachments\models\EmptyContentModel;
use open20\amos\attachments\models\File;
use open20\amos\attachments\models\FileRefs;
use open20\amos\attachments\models\search\FileSearch;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\utilities\SortModelsUtility;
use open20\amos\core\utilities\ZipUtility;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\image\drivers\Image_GD;
use yii\image\drivers\Image_Imagick;
use yii\image\ImageDriver;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class FileController
 * @package open20\amos\attachments\controllers
 */
class FileController extends Controller
{
    use FileModuleTrait;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['download', 'delete', 'view'],
                'rules' => [
                    [
                        'actions' => [
                            'download',
                            'delete',
                            'view',
                            'order-attachment'
                        ],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return $this->checkAccess($rule, $action);
                        }
                    ],
                    [
                        'actions' => [
                            'upload-files',
                            'upload-for-record'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => [
                            'export',
                            'export-by-query'
                        ],
                        'allow' => true,
                        'roles' => ['ADMIN'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param $rule
     * @param $action
     * @return bool
     */
    protected function checkAccess($rule, $action)
    {
        switch ($action->id) {
            case 'view' :
                {
                    // Fire ref
                    $fileRef = FileRefs::findOne(['hash' => Yii::$app->request->get('hash')]);

                    /**
                     * If the file is not under protection
                     */
                    if (!$fileRef->protected) {
                        return true;
                    }

                    //If file exists
                    if (!$fileRef || !$fileRef->attachFile) {
                        return false;
                    }

                    // Find file
                    $file = $fileRef->attachFile;
                }
                break;
            /* case 'delete': {
              //
              } */
            default:
                {
                    // Find file
                    $file = File::findOne(['id' => Yii::$app->request->get('id')]);
                }
                break;
        }

        //If file exists
        if (!$file || !$file->id) {
            return false;
        }

        //Model namespace of the file
        $modelClass = $file->model;

        //Provvisorio
        if ($modelClass == \open20\amos\core\record\RecordDynamicModel::className() && \Yii::$app->user->can('ADMIN')) {
            return true;
        }

        /**
         * @var $model ActiveRecord
         */
        $model = $modelClass::findOne(['id' => $file->itemId]);

        //The base class name
        $baseClassName = \yii\helpers\StringHelper::basename($modelClass);

        //If is delete mode
        if ($action->id == 'delete') {
            //Delete permission name
            $updatePremission = strtoupper($baseClassName . '_UPDATE');

            //User can update?
            if (!\Yii::$app->user->can($updatePremission, ['model' => $model])) {
                return false;
            }
        } else {
            //Read permission name
            $readPremission = strtoupper($baseClassName . '_READ');
            $readPremissionAttribute = strtoupper($baseClassName . '_' . strtoupper($file->attribute) . '_READ');

            //User can read?
            if (!\Yii::$app->user->can($readPremission, ['model' => $model]) && (\Yii::$app->user->can(
                    $readPremissionAttribute, ['model' => $model]
                ))) {
                return false;
            }
        }

        //Get behaviors
        $behaviours = $model->behaviors();

        //File behavior for permission check
        $fileBehaviour = $behaviours['fileBehavior'];

        if (!empty($fileBehaviour['permissions'])) {
            $permission = $fileBehaviour['permissions'][$file->attribute];

            if (!Yii::$app->user->can($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $id
     * @param $hash
     * @param string $size
     * @return bool|\yii\console\Response|Response
     * @throws \open20\amos\core\exceptions\AmosException
     * @throws \yii\base\ErrorException
     */
    public function actionDownload($id, $hash, $size = 'original')
    {
        /** @var File $file */
        $file = File::findOne(['id' => $id, 'hash' => $hash]);

        $filePath = $this->getModule()->getFilesDirPath(
                $file->hash
            ) . DIRECTORY_SEPARATOR . $file->hash . '.' . $file->type;

        if (file_exists($filePath)) {
            if ($size == 'original' || !in_array($file->type, ['jpg', 'jpeg', 'png', 'gif'])) {
                $this->addDownloadNumber($file);
                $zipTmpFolderName = uniqid();
                if ($file->encrypted) {
                    $zipUtility = new ZipUtility(
                        [
                            'zipFileName' => $filePath,
                            'destinationFolder' => $this->getModule()->getTempPath() . DIRECTORY_SEPARATOR . $zipTmpFolderName,
                            'password' => $file->getDecryptPassword(),
                        ]
                    );
                    $zipUtility->unZip();
                    $zipTmpFile = $this->getModule()->getTempPath() . DIRECTORY_SEPARATOR . $zipTmpFolderName . DIRECTORY_SEPARATOR . $file->name . '.' . $file->type;
                    if (file_exists($zipTmpFile)) {
                        $filePath = $zipTmpFile;
                    }
                }
                return \Yii::$app->response->sendFile($filePath, "$file->name.$file->type");
            } else {
                $moduleConfig = Yii::$app->getModule('attachments')->config;
                $crops = $moduleConfig['crops'] ?: [];

                if (array_key_exists($size, $crops)) {
                    $this->addDownloadNumber($file);
                    return $this->getCroppedImage($file, $crops[$size]);
                } else {
                    throw new \Exception('Size not found - ' . $size);
                }
            }
        } else {
            return false;
        }
    }

    /**
     * @param File $file
     */
    private function addDownloadNumber($file)
    {
        try {
            $file->num_downloads++;
            $file->save(false);
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        }
    }

    /**
     * @param $hash
     * @param bool $canCache
     * @return bool|\yii\console\Response|Response
     * @throws \open20\amos\core\exceptions\AmosException
     * @throws \yii\base\ErrorException
     */
    public function actionView($hash, $canCache = false)
    {
        $module = \open20\amos\attachments\FileModule::getInstance();

        if (!empty($module->cache_age) && $canCache) {
            \Yii::$app->response->headers->set("Cache-Control", "max-age=" . $module->cache_age . ", public");
        } else {
            \Yii::$app->response->headers->set('Pragma', 'no-cache');
            \Yii::$app->response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate');
        }
        /**
         * @var $fileRef FileRefs
         */
        $fileRef = FileRefs::findOne(['hash' => $hash]);

        if ($fileRef && $fileRef->id) {
            /**
             * @var $size string Crop name
             */
            $size = $fileRef->crop;

            /** @var File $file */
            $file = $fileRef->attachFile;

            /**
             * @var $filePath string Where is located file
             */
            $filePath = $file->getPath();

            if (file_exists($filePath)) {
                if ($size == 'original' || !in_array(strtolower($file->type), ['jpg', 'jpeg', 'png', 'gif'])) {
                    $this->addDownloadNumber($file);
                    $zipTmpFolderName = uniqid();
                    if ($file->encrypted) {
                        $zipUtility = new ZipUtility(
                            [
                                'zipFileName' => $filePath,
                                'destinationFolder' => $this->getModule()->getTempPath() . DIRECTORY_SEPARATOR . $zipTmpFolderName,
                                'password' => $file->getDecryptPassword(),
                            ]
                        );
                        $zipUtility->unZip();
                        $zipTmpFile = $this->getModule()->getTempPath() . DIRECTORY_SEPARATOR . $zipTmpFolderName . DIRECTORY_SEPARATOR . $file->name . '.' . $file->type;
                        if (file_exists($zipTmpFile)) {
                            $filePath = $zipTmpFile;
                        }
                    }
                    return \Yii::$app->response->sendFile($filePath, "$file->name.$file->type");
                } else {
                    $moduleConfig = Yii::$app->getModule('attachments')->config;
                    $crops = $moduleConfig['crops'] ?: [];

                    if (json_decode($size) != null) {
                        $crops['custom'] = (array)json_decode($size);
                        $size = 'custom';
                    }

                    if (array_key_exists($size, $crops)) {
                        $this->addDownloadNumber($file);
                        return $this->getCroppedImage($file, $crops[$size]);
                    } else {
                        throw new \Exception('Size not found - ' . $size);
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param $file
     * @param $cropSettings
     * @return \yii\console\Response|Response
     * @throws \yii\base\ErrorException
     */
    public function getCroppedImage($file, $cropSettings)
    {
        $fileDir = $this->getModule()->getFilesDirPath($file->hash) . DIRECTORY_SEPARATOR;
        $filePath = $fileDir . $file->hash . '.' . $file->type;
        $cropPath = $fileDir . $file->hash . '.' . (!empty($cropSettings['width']) ? $cropSettings['width'] : '') . '.' . (!empty($cropSettings['height'])
                ? $cropSettings['height'] : '') . '.' . $file->type;

        if (file_exists($cropPath)) {
            // return \Yii::$app->response->sendFile($cropPath, "$file->name.$file->type");
        }
        //Crop and return
        $cropper = new ImageDriver();

        $configStack = [
            'width' => null,
            'height' => null,
            'master' => null,
            'crop_width' => (!empty($cropSettings['width']) ? $cropSettings['width'] : null),
            'crop_height' => (!empty($cropSettings['height']) ? $cropSettings['height'] : null),
            'crop_offset_x' => null,
            'crop_offset_y' => null,
            'rotate_degrees' => null,
            'refrect_height' => null,
            'refrect_opacity' => null,
            'refrect_fade_in' => null,
            'flip_direction' => null,
            'bg_color' => null,
            'bg_opacity' => null,
            'quality' => 100
        ];

        $cropConfig = ArrayHelper::merge($configStack, $cropSettings);

        /**
         * Extract All settings
         * Eg.
         * $cr_width
         * $cr_height
         * $cr_quality
         */
        extract($cropConfig, EXTR_PREFIX_ALL, 'cr');

        /**
         * @var $image Image_GD | Image_Imagick
         */
        $image = $cropper->load($filePath);

        $image->resize($cr_width, $cr_height, $cr_master);

        if ($cr_crop_width && $cr_crop_height) {
            $image->crop($cr_crop_width, $cr_crop_height, $cr_crop_offset_x, $cr_crop_offset_y);
        }
        if ($cr_rotate_degrees) {
            $image->rotate($cr_rotate_degrees);
        }
        if ($cr_refrect_height) {
            $image->reflection($cr_refrect_height, $cr_refrect_opacity, $cr_refrect_fade_in);
        }
        if ($cr_flip_direction) {
            $image->flip($cr_flip_direction);
        }
        if ($cr_bg_color) {
            $image->background($cr_bg_color, $cr_bg_opacity);
        }
        $image->save($cropPath, $cr_quality);
        //Return the new image
        return \Yii::$app->response->sendFile($cropPath, "$file->name.$file->type");
    }

    /**
     * @param $id
     * @param $item_id
     * @param $model
     * @param $attribute
     * @return bool|Response
     * @throws \Exception
     */
    public function actionDelete($id, $item_id, $model, $attribute)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($this->getModule()->detachFile($id)) {
            if (Yii::$app->request->isAjax) {
                return true;
            } else {
                return $this->goBack((!empty(Yii::$app->request->referrer) ? Yii::$app->request->referrer : null));
            }
        } else {
            if (Yii::$app->request->isAjax) {
                return false;
            } else {
                return $this->goBack((!empty(Yii::$app->request->referrer) ? Yii::$app->request->referrer : null));
            }
        }
    }

    /**
     * @param $filename
     * @return \yii\console\Response|Response
     * @throws \Exception
     */
    public function actionDownloadTemp($filename)
    {
        $filePath = $this->getModule()->getUserDirPath() . DIRECTORY_SEPARATOR . $filename;

        return \Yii::$app->response->sendFile($filePath, $filename);
    }

    /**
     * @param $filename
     * @return array
     * @throws \Exception
     */
    public function actionDeleteTemp($filename)
    {
        $userTempDir = $this->getModule()->getUserDirPath();
        $filePath = $userTempDir . DIRECTORY_SEPARATOR . $filename;
        unlink($filePath);
        if (!count(FileHelper::findFiles($userTempDir))) {
            rmdir($userTempDir);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [];
    }

    public function actionUploadForRecord()
    {
        //
    }

    /**
     * @return false|string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUploadFiles()
    {
        reset($_FILES);
        $temp = current($_FILES);
        if (is_uploaded_file($temp['tmp_name'])) {
            // Sanitize input
            if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
                header("HTTP/1.1 400 Invalid file name.");
                return 'Invalid file name.';
            }

            // Verify extension
            if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
                header("HTTP/1.1 400 Invalid extension.");
                return 'Invalid extension.';
            }
            // Accept upload if there was no origin, or if it is an accepted origin
            $filetowrite = $this->getModule()->getUserDirPath() . $temp['name'];
            move_uploaded_file($temp['tmp_name'], $filetowrite);
            $file = $this->getModule()->attachFile($filetowrite, new EmptyContentModel());
            if (!is_null($file)) {
                return json_encode(array('location' => $file->getWebUrl('original', true, true)));
            }
            header("HTTP/1.1 500 Server Error");
        } else {
            // Notify editor that the upload failed
            header("HTTP/1.1 500 Server Error");
        }
        return 'Server Error';
    }

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = ($action->id !== "upload-files");
        return parent::beforeAction($action);
    }

    public function actionExport()
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(120);

        $this->layout = '@vendor/open20/amos-layout/src/views/layouts/main';
        $query = File::find();

        $modelSearch = new FileSearch();
        $dataProvider = $modelSearch->search(\Yii::$app->request->getQueryParams());
        /*
          $dataProvider = new ActiveDataProvider(
          [
          'query' => $query,
          'pagination' => [
          'pageSize' => 300
          ]
          ]
          ); */

        if (Yii::$app->request->isPost) {
            $zipPath = $this->zipResult($dataProvider);

            \Yii::$app->response->sendFile($zipPath);
        } else {
            return $this->render(
                'export',
                [
                    'model' => $modelSearch,
                    'dataProvider' => $dataProvider
                ]
            );
        }
    }

    public function actionExportByQuery()
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(1200);

        $this->layout = '@vendor/open20/amos-layout/src/views/layouts/form';

        if (Yii::$app->request->isPost) {
            $connection = Yii::$app->db;
            $command = $connection->createCommand(Yii::$app->request->post('query'));

            $result = $command->queryAll();

            $dataProvider = new ArrayDataProvider(['allModels' => $result]);

            $zipPath = $this->zipResult($dataProvider);

            \Yii::$app->response->sendFile($zipPath);
        } else {
            return $this->render(
                'export-by-query'
            );
        }
    }

    /**
     * @param $dataProvider ActiveDataProvider|ArrayDataProvider
     */
    public function zipResult($dataProvider)
    {
        $zip = new \ZipArchive();

        $dirUploads = realpath(Yii::getAlias('@vendor/../common/uploads//temp'));
        $filename = $dirUploads . "/mediaExport.zip";

        if ($zip->open($filename, \ZipArchive::CREATE) !== TRUE) {
            exit("cannot create <$filename>\n");
        }

        $elements = $dataProvider->getModels();

        if ($dataProvider instanceof ArrayDataProvider) {
            foreach ($elements as $fileArray) {
                $file = File::findOne(['id' => $fileArray['id']]);

                if ($file && $file->id && file_exists($file->path)) {
                    $zip->addFile($file->path, $file->name . '.' . $file->type);
                }
            }
        } else {
            foreach ($elements as $file) {
                if ($file && $file->id && file_exists($file->path)) {
                    $zip->addFile($file->path, $file->name . '.' . $file->extension);
                }
            }
        }

        $status = $zip->close();

        return $filename;
    }

    /**
     * This action order the attachment provided accordingly with direction.
     * @param int $id
     * @param string $direction
     * @return bool|Response
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionOrderAttachment($id, $direction)
    {
        if (!Yii::$app->request->isPost) {
            throw new BadRequestHttpException(FileModule::t('amosattachments', 'The request must be ajax'));
        }

        $attachFile = File::findOne($id);
        if (is_null($attachFile)) {
            throw new NotFoundHttpException(BaseAmosModule::t('amoscore', 'The requested page does not exist.'));
        }

        $orderList = $attachFile->getAttachmentWithBrothers()->select(['id'])->orderBy(['sort' => SORT_ASC])->column();
        $sortUtility = new SortModelsUtility([
            'model' => $attachFile,
            'modelSortField' => 'sort',
            'direction' => $direction,
            'orderList' => $orderList
        ]);
        $ok = $sortUtility->reorderModels();
        if ($ok) {
            if (Yii::$app->request->isAjax) {
                return true;
            } else {
                return $this->goBack((!empty(Yii::$app->request->referrer) ? Yii::$app->request->referrer : null));
            }
        } else {
            if (Yii::$app->request->isAjax) {
                return false;
            } else {
                return $this->goBack((!empty(Yii::$app->request->referrer) ? Yii::$app->request->referrer : null));
            }
        }
    }
}
