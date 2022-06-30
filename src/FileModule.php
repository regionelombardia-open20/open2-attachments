<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments
 * @category   CategoryName
 */

namespace open20\amos\attachments;

use open20\amos\attachments\models\File;
use open20\amos\core\module\AmosModule;

use open20\amos\core\utilities\ZipUtility;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\log\Logger;

/**
 * Class FileModule
 * @package open20\amos\attachments
 */
class FileModule extends AmosModule
{
    /**
     * seconds Cache-Control duration
     * @var integer
     */
    public $cache_age = null;
    /**
     * Папка, в которую прокинут линк для прямого доступа по http
     * @var string
     */
    public $webDir = 'files';

    public $controllerNamespace = 'open20\amos\attachments\controllers';

    public $storePath = '@app/uploads/store';

    public $tempPath = '@app/uploads/temp';

    public $rules = [];

    public $tableName = 'attach_file';

    public $config = [];

    public $disableGallery = true;

    public $enableSingleGallery = true;

    /**
     * @var null|\amos\statistics\models\AttachmentsStatsInterface
     */
    public $statistics = null;

    public static function getModuleName()
    {
        return "attachments";
    }

    public function getWidgetIcons()
    {
        return [];
    }

    public function getWidgetGraphics()
    {
        return [];
    }

    /**
     * @throws Exception
     */
    public function init()
    {
        parent::init();

        if (empty($this->storePath) || empty($this->tempPath)) {
            throw new Exception(FileModule::t('amosattachments', 'Setup {storePath} and {tempPath} in module properties'));
        }

        //Configuration
        $config = require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php');
        \Yii::configure($this, ArrayHelper::merge($config, $this));

        $this->rules = ArrayHelper::merge(['maxFiles' => 3], $this->rules);
        $this->defaultRoute = 'attachments';
    }

    /**
     * @param string $suffix
     * @return string
     * @throws \Exception
     */
    public function getUserDirPath($suffix = '')
    {
        $sessionId = md5(0);

        if(\Yii::$app->has('session')) {
            \Yii::$app->session->open();
            $sessionId = \Yii::$app->session->id;
            \Yii::$app->session->close();
        }

        $userDirPath = $this->getTempPath() . DIRECTORY_SEPARATOR . $sessionId . $suffix;
        
        //Try dir creation
        FileHelper::createDirectory($userDirPath, 0777);

        //Check if the dir has been created
        if(!is_dir($userDirPath)) {
            throw new \Exception("Unable to create Upload Direcotory '{$userDirPath}'");
        }

        return $userDirPath . DIRECTORY_SEPARATOR;
    }

    /**
     * @return bool|string
     */
    public function getTempPath()
    {
        return \Yii::getAlias($this->tempPath);
    }

    /**
     * @param $obj
     * @return string
     */
    public function getShortClass($obj)
    {
        $className = get_class($obj);
        if (preg_match('@\\\\([\w]+)$@', $className, $matches)) {
            $className = $matches[1];
        }
        return $className;
    }

    /**
     * @param $filePath string
     * @param $owner
     * @param $attribute
     * @return bool|File
     * @throws \Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function attachFile($filePath, $owner, $attribute = 'file', $dropOriginFile = true, $saveWithoutModel = false, $encrypt = false)
    {
        if(!$saveWithoutModel) {
            if (!$owner->id) {
                throw new \Exception(FileModule::t('amosattachments', 'Owner must have id when you attach file'));
            }
        }

        if (!file_exists($filePath)) {
            throw new \Exception(FileModule::t('amosattachments', 'File not exist :') . $filePath);
        }
  
        //File infos
        $fileType = pathinfo($filePath, PATHINFO_EXTENSION);
        $fileName = pathinfo($filePath, PATHINFO_FILENAME);
        $dirName = pathinfo($filePath, PATHINFO_DIRNAME);

        //Create a clean name for file
        $cleanName = preg_replace("([\.]{2,})", '_',preg_replace("([^\w\d\-_~,;\[\]\(\).])", '_', $fileName));

        //New location for the file
        $cleanFilePath = $dirName .DIRECTORY_SEPARATOR.$cleanName.'.'.$fileType;

        if ($encrypt) {
            $fileTmp = new File();
            $zipTempFileName = uniqid();
            $zipTempCompletePath = $this->getTempPath() . DIRECTORY_SEPARATOR . $zipTempFileName . '.zip';
            $zipUtility = new ZipUtility([
                'filesToZip' => $filePath,
                'zipFileName' => $zipTempFileName,
                'destinationFolder' => $this->getTempPath(),
                'password' => $fileTmp->getDecryptPassword(),
            ]);
            $zipUtility->createZip();
            if (file_exists($zipTempCompletePath)) {
                $filePath = $zipTempCompletePath;
            }
        }

        //Rename current file to avoid any problem
        rename($filePath, $cleanFilePath);
        // compress it!
        if (in_array($fileType, ['jpg'])) {
            $image = imagecreatefromjpeg($cleanFilePath);
            imagejpeg($image, $cleanFilePath, 80);
        }
        
        //SystemMd5Sum
        if (strtoupper(PHP_OS) == 'WINNT') {
            $fileHash = md5(file_get_contents($cleanFilePath));
        } else {
            $escapedFilePath = escapeshellarg($cleanFilePath);
            exec("md5sum {$escapedFilePath}", $fileHash);

            $filesize = filesize($cleanFilePath);
            $fileHash = md5(reset($fileHash). $filesize);
        }

        //New file infos
        $newFileName = $fileHash . '.' . $fileType;
        $fileDirPath = $this->getFilesDirPath($fileHash);

        $newFilePath = $fileDirPath . DIRECTORY_SEPARATOR . $newFileName;

        if (!file_exists($cleanFilePath)) {
            throw new \Exception(FileModule::t('amosattachments', 'Cannot copy file! ') . $cleanFilePath . FileModule::t('amosattachments', ' to ') . $newFilePath);
        }

        if($saveWithoutModel){
            $ownerId = null;
        } else {
            $ownerId = $owner->id;
        }

        $ownerClass = $this->getClass($owner);

        $tableNameOwner = null;
        if($owner instanceof \open20\amos\core\record\RecordDynamicModel && method_exists($owner, 'getTableName')){
            $tblName = $owner->getTableName();
            if(!empty($tblName)){
                $tableNameOwner = $tblName;
            }
        }

        $query =  File::find()->andWhere([
            'hash' => $fileHash,
            'itemId' => $ownerId,
            'attribute' => $attribute,
            'model' => $ownerClass,
        ]);
        if(!is_null($tableNameOwner)){
            $query->andFilterWhere(['table_name_form' => $tableNameOwner]);
        }
        $exists =$query->one();

        if (!$exists) {

            copy($cleanFilePath, $newFilePath);

            $file = new File();

            $file->name = $fileName;
            $file->model = $ownerClass;
            $file->itemId = $ownerId;
            $file->hash = $fileHash;
            $file->size = filesize($cleanFilePath);
            $file->type = $fileType;
            $file->mime = FileHelper::getMimeType($cleanFilePath);
            $file->attribute = $attribute;
            if(!empty($tableNameOwner)){
            $file->table_name_form = $tableNameOwner;
            }
            if ($encrypt) {
                $file->encrypted = File::IS_ENCRYPTED;
            }

            /** @var ActiveQuery $query */
            $query = File::find();
            $query->andWhere(['model' => $ownerClass]);
            $query->andWhere(['attribute' => $attribute]);
            $query->andWhere(['itemId' => $ownerId]);
            $maxSort = $query->max('sort');
            $file->sort = ($maxSort + 1);

            if ($file->save()) {
                if($dropOriginFile) {
                    unlink($cleanFilePath);
                }

                try {
                    if(!is_null($this->statistics))
                    {
                        $stat = \Yii::createObject($this->statistics);
                        $ok = $stat->save($file);
                        if (!$ok) {
                            \Yii::getLogger()->log(FileModule::t('amosattachments', 'Statistics: error while saving'), Logger::LEVEL_WARNING);
                        }
                    }
                } catch (\Exception $exception) {
                    \Yii::getLogger()->log($exception->getMessage(), Logger::LEVEL_ERROR);
                }

                return $file;
            } else {
                if (count($file->getErrors()) > 0) {
                    $errors = $file->getErrors();
                    $ar = array_shift($errors);

                    if($dropOriginFile) {
                        unlink($newFilePath);
                    }

                    if($session = \Yii::$app->has('session')) {
                        \Yii::$app->session->addFlash('error', array_shift($ar));
                    }
                }

                return false;
            }
        } else {
            if($dropOriginFile) {
                unlink($cleanFilePath);
            }
            if ($encrypt) {
                $exists->encrypted = File::IS_ENCRYPTED;
                $exists->save(false);
            }
            return $exists;
        }
    }

    /**
     * @param $fileHash
     * @param $useStorePath
     * @return string
     */
    public function getFilesDirPath($fileHash, $useStorePath = true)
    {
        if ($useStorePath) {
            $path = $this->getStorePath() . DIRECTORY_SEPARATOR . $this->getSubDirs($fileHash);
        } else {
            $path = DIRECTORY_SEPARATOR . $this->getSubDirs($fileHash);
        }

        FileHelper::createDirectory($path, 0777);

        return $path;
    }

    /**
     * @return bool|string
     */
    public function getStorePath()
    {
        return \Yii::getAlias($this->storePath);
    }

    /**
     * @param $fileHash
     * @param int $depth
     * @return string
     */
    public function getSubDirs($fileHash, $depth = 3)
    {
        $depth = min($depth, 9);
        $path = '';

        for ($i = 0; $i < $depth; $i++) {
            $folder = substr($fileHash, $i * 3, 2);
            $path .= $folder;
            if ($i != $depth - 1) $path .= DIRECTORY_SEPARATOR;
        }

        return $path;
    }

    /**
     * @param \yii\base\BaseObject $obj
     * @return string
     */
    public function getClass($obj)
    {
        $className = $obj::className();

        return $className;
    }

    /**
     * @param $id
     */
    public function detachFile($id)
    {
        /** @var File $file */
        $file = File::findOne(['id' => $id]);
        if(!is_null($file))
        {
            $anyMore = File::findAll(['hash' => $file->hash]);
            $filePath = $this->getFilesDirPath($file->hash) . DIRECTORY_SEPARATOR . $file->hash . '.' . $file->type;
            if (file_exists($filePath) && count($anyMore) == 1) {
                unlink($filePath);
            }

            try {
                if(!is_null($this->statistics))
                {
                    $stat = \Yii::createObject($this->statistics);
                    /** @var \amos\statistics\models\AttachmentsStatsInterface $stat */
                    $ok = $stat->delete($file);
                    if (!$ok)
                    {
                        \Yii::getLogger()->log(FileModule::t('amosattachments', 'Statistics: error while saving'), Logger::LEVEL_WARNING);
                    }
                }
            } catch (\Exception $exception) {
                \Yii::getLogger()->log($exception->getMessage(), Logger::LEVEL_ERROR);
            }

            $file->delete();
        }
    }

    /**
     * @param File $file
     * @return string
     */
    public function getWebPath(File $file)
    {
        $fileName = $file->hash . '.' . $file->type;
        $webPath = '/' . $this->webDir . '/' . $this->getSubDirs($file->hash) . '/' . $fileName;
        return $webPath;
    }

    public function getDefaultModels() {
        return File::classname();
    }
}
