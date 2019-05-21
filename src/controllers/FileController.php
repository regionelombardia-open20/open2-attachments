<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\attachments
 * @category   CategoryName
 */

namespace lispa\amos\attachments\controllers;

use lispa\amos\attachments\FileModuleTrait;
use lispa\amos\attachments\models\File;
use lispa\amos\attachments\models\FileRefs;
use lispa\amos\attachments\models\UploadForm;
use Yii;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\image\drivers\Image_GD;
use yii\image\drivers\Image_Imagick;
use yii\image\ImageDriver;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use lispa\amos\attachments\models\EmptyContentModel;

/**
 * Class FileController
 * @package lispa\amos\attachments\controllers
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
                        'actions' => ['download', 'delete', 'view'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return $this->checkAccess($rule, $action);
                        }
                    ],
                    [
                        'actions' => [
                            'upload-files',
                            'upload-for-record',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
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
            case 'view' : {
                // Fire ref
                $fileRef = FileRefs::findOne(['hash' => Yii::$app->request->get('hash')]);

                /**
                 * If the file is not under protection
                 */
                if(!$fileRef->protected) {
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
            /*case 'delete': {
                //
            }*/
            default: {
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

            //User can read?
            if (!\Yii::$app->user->can($readPremission, ['model' => $model])) {
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
     * @return Response|bool
     * @throws \Exception
     */
    public function actionDownload($id, $hash, $size = 'original')
    {
        /** @var File $file */
        $file = File::findOne(['id' => $id, 'hash' => $hash]);

        $filePath = $this->getModule()->getFilesDirPath($file->hash) . DIRECTORY_SEPARATOR . $file->hash . '.' . $file->type;

        if (file_exists($filePath)) {
            if ($size == 'original' || !in_array($file->type, ['jpg', 'jpeg', 'png', 'gif'])) {
               $this->addDownloadNumber($file);
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
        } else
            return false;
    }
    
    /**
     * 
     * @param type $file
     */
    private function addDownloadNumber($file){
        try{
            $file->num_downloads++;
            $file->save(false);
        }catch(\Exception $ex){
            Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        }
    }

    /**
     * @param $hash
     * @return $this|bool
     * @throws \Exception
     */
    public function actionView($hash, $canCache=false)
    {
        $module = \lispa\amos\attachments\FileModule::getInstance();
        
        if(!empty($module->cache_age) && $canCache){
            \Yii::$app->response->headers->set("Cache-Control", "max-age=" . $module->cache_age.", public");
        }else{
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
            } else
                return false;
        } else {
            return false;
        }
    }

    /**
     * @param $file
     * @param $cropSettings
     * @return $this
     */
    public function getCroppedImage($file, $cropSettings)
    {
        $fileDir = $this->getModule()->getFilesDirPath($file->hash) . DIRECTORY_SEPARATOR;
        $filePath = $fileDir . $file->hash . '.' . $file->type;
        $cropPath = $fileDir . $file->hash . '.' . $cropSettings['width'] . '.' . $cropSettings['height'] . '.' . $file->type;

        if (file_exists($cropPath)) {
           // return \Yii::$app->response->sendFile($cropPath, "$file->name.$file->type");
        }
        //Crop and return
        $cropper = new ImageDriver();

        $configStack = [
            'width' => null,
            'height' => null,
            'master' => null,
            'crop_width' => $cropSettings['width'],
            'crop_height' => $cropSettings['height'],
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
     * @return $this
     */
    public function actionDownloadTemp($filename)
    {
        $filePath = $this->getModule()->getUserDirPath() . DIRECTORY_SEPARATOR . $filename;

        return \Yii::$app->response->sendFile($filePath, $filename);
    }

    /**
     * @param $filename
     * @return array
     */
    public  function actionDeleteTemp($filename)
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

    public function actionUploadForRecord() {
        //
    }
    
    
    /**
     * 
     * @return mixed
     */
    public function actionUploadFiles()
    {

       reset ($_FILES);
       $temp = current($_FILES);
       if (is_uploaded_file($temp['tmp_name'])){
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
         if(!is_null($file)){
             return json_encode(array('location' => $file->getWebUrl('original',true, true)));
         }   
         header("HTTP/1.1 500 Server Error");
       } else {
         // Notify editor that the upload failed
         header("HTTP/1.1 500 Server Error");
       }
       return 'Server Error';
    }
    
    /**
     * 
     * @param $action
     * @return 
     */
    public function beforeAction($action) {
        $this->enableCsrfValidation = ($action->id !== "upload-files"); 
        return parent::beforeAction($action);
    }
}
