<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\behaviors
 * @category   CategoryName
 */

namespace open20\amos\attachments\behaviors;

use open20\amos\attachments\components\CustomUploadFile;
use open20\amos\attachments\FileModule;
use open20\amos\attachments\FileModuleTrait;
use open20\amos\attachments\models\AttachGalleryImage;
use open20\amos\attachments\models\File;
use open20\amos\core\views\toolbars\StatsToolbarPanels;
use open20\amos\core\utilities\ClassUtility;

use Imagine\Image\Box;
use Imagine\Image\Point;

use yii\base\Behavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\validators\FileValidator;
use yii\web\UploadedFile;
use yii\imagine\Image;
use yii\helpers\Json;

/**
 * Class FileBehavior
 * @property ActiveRecord $owner
 * @package file\behaviors
 */
class FileBehavior extends Behavior
{
    /**
     *
     */
    use FileModuleTrait;

    /**
     * @var array $permissions
     */
    public $permissions = [];

    /**
     * @var array $rules
     */
    var $rules = [];

    /**
     * @var array $fileValidators
     */
    private $fileValidators = [];

    /**
     * @var array $fileAttributes
     */
    private $fileAttributes = [];

    /**
     * @var array Attibutes to be overriden
     */
    private $overrideAttributes = [];

    /**
     * @var array $encryptedAttributes
     */
    public $encryptedAttributes = [];

    /**
     * Add files attributes as avaiable property for Getter
     * @return bool
     */
    public function canGetProperty($name, $checkVars = true)
    {
        $fileAttributes = self::getFileAttributes();

        if (in_array($name, $fileAttributes)) {
            return true;
        }

        return parent::canGetProperty($name, $checkVars);
    }

    /**
     * Add files attributes as avaiable property for Setter
     * @return bool
     */
    public function canSetProperty($name, $checkVars = true)
    {
        $fileAttributes = self::getFileAttributes();

        if (in_array($name, $fileAttributes)) {
            return true;
        }

        return parent::canSetProperty($name, $checkVars);
    }

    /**
     * Parse file behaviors and find for the choosed attribute for hasOne
     * or hasMultiple files relation
     * @param string $name
     * @return array|mixed
     * @throws \yii\base\UnknownPropertyException
     */
    public function __get($name)
    {
        /**
         * @var FileValidator $fileValidator
         */
        $fileValidator = $this->getFileValidator($name);

        if ($fileValidator) {
            if(isset($this->overrideAttributes[$name])) {
                return $this->overrideAttributes[$name];
            }

            if ($fileValidator->maxFiles == 1) {
                return $this->hasOneFile($name);
            } else {
                return $this->hasMultipleFiles($name);
            }
        }

        //No results? return the parent getter
        return parent::__get($name); // TODO: Change the autogenerated stub
    }

    /**
     * Setter is an override tool for validation
     */
    public function __set($name, $value)
    {
        $this->overrideAttributes[$name] = $value;

        return true;
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        $events = [
            ActiveRecord::EVENT_AFTER_DELETE => 'deleteUploads',
            ActiveRecord::EVENT_AFTER_INSERT => 'saveUploads',
            ActiveRecord::EVENT_AFTER_UPDATE => 'saveUploads',
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'evalAttributes',
            ActiveRecord::EVENT_AFTER_VALIDATE => 'unevalAttributes'
        ];

        return $events;
    }



    /**
     * Iterate files and save by attribute
     * @param $event
     */
    public function saveUploads($event)
    {
        if (\Yii::$app->request->isConsoleRequest || !\Yii::$app->request->isPost) {
            return false;
        }

        $attributes = $this->getFileAttributes();

        if (!empty($attributes)) {
            foreach ($attributes as $attribute) {
                $this->saveAttributeUploads($attribute);
            }
        }
    }

    /**
     * Return array of attributes which may contain files
     * @return array
     */
    public function getFileAttributes()
    {
        $validators = $this->owner->getValidators();

        //Array of attributes
        $fileAttributes = [];

        //has file validator?
        $this->fileValidators = $this->getFileValidators($validators);

        foreach ($this->fileValidators as $fileValidator) {
            if (!empty($fileValidator)) {
                foreach ($fileValidator->attributes as $attribute) {
                    $fileAttributes[] = $attribute;
                }
            }
        }
        return $fileAttributes;
    }

    /**
     * @param $attributeName
     * @return bool|mixed|FileValidator|\yii\validators\Validator
     */
    public function getFileValidator($attributeName)
    {
        $fileValidators = $this->owner->getValidators();

        if (!empty($fileValidators)) {
            foreach ($fileValidators as $fileValidator) {
                /**
                 * @var FileValidator $fileValidator
                 */
                if (!empty($fileValidator) && $fileValidator instanceof FileValidator) {
                    foreach ($fileValidator->attributes as $attribute) {
                        if ($attribute == $attributeName) {
                            return $fileValidator;
                        }
                    }
                }
            }
        }
        return false;
    }

    /**
     * Check if owner model has file validator
     * @param ArrayObject|\yii\validators\Validator[]
     * @return \yii\validators\Validator[]
     */
    public function getFileValidators($validators)
    {
        $fileValidators = [];

        foreach ($validators as $validator) {
            /** @var \yii\validators\Validator $validator */
            $classname = $validator::className();

            if ($classname == 'yii\validators\FileValidator') {
                $fileValidators[] = $validator;
            }
        }

        return $fileValidators;
    }

    /**
     *
     * @param type $attribute
     * @return boolean
     */
    protected function saveAttributeUploads($attribute)
    {
        //Find shot model class name
        $ownerName = (new \ReflectionClass($this->owner))->getShortName();

        /**
         * @var UploadedFile[] $files
         */
        $files = UploadedFile::getInstancesByName($attribute)
            ?
            : UploadedFile::getInstancesByName("{$ownerName}[{$attribute}]");

        /**
         * BASE64 Encoded Files
         */
        $postData = \Yii::$app->request->post($ownerName);
        $fileAsString = (isset($postData[$attribute]) && !is_array($postData[$attribute]))
            ? $postData[$attribute]
            : null;

        if (!empty($fileAsString)) {
            $decodedFileTempName = md5(time().$attribute);
            $decodedFilePath = sys_get_temp_dir() . '/' . $decodedFileTempName;

            //Decode
            file_put_contents($decodedFilePath, base64_decode($fileAsString));

            //Get File Type
            $mime = mime_content_type($decodedFilePath);
            $mimes = new \Mimey\MimeTypes;
            $extension = $mimes->getExtension($mime);

            //Create new file instance
            $newFile = new CustomUploadFile();
            $newFile->name = $decodedFileTempName.'.'.$extension;
            $newFile->tempName = $decodedFilePath;
            $newFile->type = $mime;

            $files[] = $newFile;
        }

        /**
         * @var FileValidator $fileValidator
         */
        $fileValidator = $this->getFileValidator($attribute);

        //Crop data
        $cropData = \Yii::$app->request->post("{$attribute}_data");

        //Upload file from gallery
        $csrfParam = \Yii::$app->request->csrfParam;
        $csrf = \Yii::$app->request->post($csrfParam);
        $dataImage = \Yii::$app->session->get($csrf);

        if(!empty($dataImage)) {
            $this->saveFileFormGallery($attribute, $csrf, $dataImage);
        }

        foreach ($files as $file) {
            if (
                $fileValidator
                && $fileValidator->maxFiles == 1
                && file_exists($file->tempName)
            ) {
                //Drop to make space for new image
                $this->deleteAttachments($this->owner,$attribute);
            }

            if ($cropData) {
                $cropInfo = Json::decode($cropData);
                if (
                    (
                        isset($cropInfo['width'])
                        &&  ($cropInfo['width'] > 0)
                    )
                    && ((isset($cropInfo['height']))
                    &&  ($cropInfo['height'] > 0))
                ) {
                    $this->cropImage($file, $cropData);
                }
            }

            if (!$file->saveAs($this->getModule()->getUserDirPath($attribute) . $file->name)) {
                continue;
            }
        }

        if ($this->owner->isNewRecord) {
            return true;
        }

        $userTempDir = $this->getModule()->getUserDirPath($attribute);

        //If the firectory doen't exists go out
        if(!is_dir($userTempDir)) {
            return false;
        }

        foreach (FileHelper::findFiles($userTempDir) as $file) {
            if (!$this->getModule()->attachFile(
                $file,
                $this->owner,
                $attribute,
                true,
                false, (in_array($attribute, $this->encryptedAttributes)))
            ) {
                $this->owner->addError($attribute, 'File upload failed.');
                return true;
            }
        }
    }

    /**
     * Drop all attachaments (Workaround for multiple attachaments on single file input)
     * @param ActiveRecord $model
     * @param $attribute
     * @return bool
     */
    private function deleteAttachments($model, $attribute) {
        return File::deleteAll([
            'model' => get_class($model),
            'attribute' => $attribute,
            'item_id' => $model->id
        ]);
    }

    /**
     *
     * @param UploadedFile $file
     * @param type $data
     */
    protected function cropImage(UploadedFile $file, $data)
    {
        //if temp file from post attributes is still present, the cropped image has to be saved
        if(file_exists($file->tempName)) {
            //The image file to be cropped
            $image = Image::getImagine()->open($file->tempName);

            // rendering information about crop of ONE option
            $cropInfo = Json::decode($data);

            if(isset($cropInfo['rotate'])) {
                $image->rotate($cropInfo['rotate']);
            }

            $cropInfo['x'] = abs($cropInfo['x']); //begin position of frame crop by X
            $cropInfo['y'] = abs($cropInfo['y']); //begin position of frame crop by Y
            $cropInfo['width'] = (int)$cropInfo['width']; //width of cropped image
            $cropInfo['height'] = (int)$cropInfo['height']; //height of cropped image

            //saving thumbnail
            $cropSizeThumb = new Box($cropInfo['width'], $cropInfo['height']); //frame size of crop
            $cropPointThumb = new Point($cropInfo['x'], $cropInfo['y']);
            $pathThumbImage = $file->tempName . '_thumb_' . $file->name;//$file->tempName;

            $image
                ->crop($cropPointThumb, $cropSizeThumb)
                ->save($pathThumbImage, ['quality' => 100]);

            @unlink($file->tempName);
            rename($pathThumbImage, $file->tempName);
        }
    }

    /**
     * When update save files before the validation
     * @param $event
     * @return bool|void
     */
    public function evalAttributes($event)
    {
        $attributes = $this->getFileAttributes();
        $sessImg    = null;
        if (\Yii::$app->request->isPost) {

            $csrfParam = \Yii::$app->request->csrfParam;

            $csrf    = \Yii::$app->request->post($csrfParam);
            $sessImg = \Yii::$app->session->get($csrf);
        }

        if (!empty($attributes)) {
            foreach ($attributes as $attribute) {
                $files = UploadedFile::getInstancesByName(
                        ClassUtility::getClassBasename($this->owner->className())."[".$attribute."]"
                );

                if (!empty($sessImg) && is_array($sessImg) && array_key_exists($attribute, $sessImg)) {
                    if (!empty($sessImg[$attribute]['id'])) {
                        $imgGallery = AttachGalleryImage::findOne($sessImg[$attribute]['id']);
                        if (!empty($imgGallery)) {

                            if (array_key_exists($attribute.'_data', \Yii::$app->request->post())) {
                                $newBody = [];
                                foreach (\Yii::$app->request->post() as $k => $post) {
                                    $newBody[$k] = $post;
                                }
                                $newBody[$attribute.'_data'] = 'ok';
                                \Yii::$app->request->setBodyParams($newBody);
                            }
                        }
                    }
                }

                if (!empty($files)) {
                    $maxFiles = 1;
                    foreach ($this->fileValidators as $fileValidator) {

                        if (!empty($fileValidator)) {
                            if (in_array($attribute, $fileValidator->attributes)) {
                                $maxFiles = $fileValidator->maxFiles;
                            }
                        }
                    }
                    $setter = 'set'.ucfirst($attribute);
                    if (method_exists($this->owner, $setter)) {
                        $this->owner->{$setter}($maxFiles == 1 ? reset($files) : $files);
                    } else {
                        if ($maxFiles != 1) {
                            $this->owner->{$attribute} = [];

                            //List of files to be setted
                            $attributeFiles = [];

                            foreach ($files as $file) {
                                $attributeFiles[] = $file;
                            }

                            $this->owner->{$attribute} = $attributeFiles;
                        } else {
                            $this->owner->{$attribute} = reset($files);
                        }
                    }
                }
            }
        }
    }

    /**
     * Unset attributes from override
     * @return bool
     */
    public function unevalAttributes()
    {
        $attributes = $this->getFileAttributes();

        foreach ($attributes as $attribute) {
            //Enruse var is unsetted from override
            if (isset($this->overrideAttributes[$attribute])) {
                unset($this->overrideAttributes[$attribute]);
            }
        }

        return true;
    }

    /**
     * @param $event
     */
    public function deleteUploads($event)
    {
        $files = $this->getFiles();

        foreach ($files as $file) {
            $this->getModule()->detachFile($file->id);
        }
    }

    /**
     * @param string $andWhere
     * @return File[]
     */
    public function getFiles($andWhere = '')
    {
        return $this->getFilesQuery()->all();
    }

    /**
     * @param string $andWhere
     * @return \yii\db\ActiveQuery
     */
    public function getFilesQuery($andWhere = '')
    {
        $fileQuery = File::find()
            ->where(
                [
                    'item_id' => $this->owner->getAttribute('id'),
                    'model' => $this->getModule()->getClass($this->owner)
                ]
            );
        $fileQuery->orderBy('is_main DESC, sort DESC');

        if ($andWhere) {
            $fileQuery->andWhere($andWhere);
        }

        return $fileQuery;
    }

    /**
     * DEPRECATED
     */
    public function getSingleFileByAttributeName($attribute = 'file', $sort = 'id')
    {
        $query = $this->getFilesByAttributeName($attribute, $sort);

        //Single result mode
        $query->multiple = false;

        return $this->hasOneFile($attribute, $sort);
    }

    /**
     * DEPRECATED
     */
    public function getFilesByAttributeName($attribute = 'file', $sort = 'id')
    {
        return $this->hasMultipleFiles($attribute, $sort);
    }

    /**
     * @param string $attribute
     * @param string $sort
     * @return \yii\db\ActiveQuery
     */
    public function hasMultipleFiles($attribute = 'file', $sort = 'id')
    {
        $fileQuery = File::find()
            ->where([
                'item_id' => $this->owner->id,
                'model' => $this->getModule()->getClass($this->owner),
                'attribute' => $attribute,
            ]);

        $fileQuery->orderBy([$sort => SORT_ASC]);

        //Single result mode
        $fileQuery->multiple = true;

        return $fileQuery;
    }

    /**
     * @param string $attribute
     * @param string $sort
     * @return \yii\db\ActiveQuery
     */
    public function hasOneFile($attribute = 'file', $sort = 'id')
    {
        $query = $this->hasMultipleFiles($attribute, $sort);

        //Single result mode
        $query->multiple = false;

        return $query;
    }

    /**
     * @param string $attribute
     * @return array
     */
    public function getInitialPreviewByAttributeName($attribute = 'file', $size = null)
    {
        $initialPreview = [];
        $userTempDir = $this->getModule()->getUserDirPath($attribute);

        if(is_dir($userTempDir)) {
            foreach (FileHelper::findFiles($userTempDir) as $file) {

                if (substr(FileHelper::getMimeType($file), 0, 5) === 'image') {
                    $initialPreview[] = Html::img(
                        [
                            '/'
                            . FileModule::getModuleName()
                            . '/file/download-temp',
                            'filename' => basename($file)
                        ],
                        ['class' => 'file-preview-image']
                    );
                } else {
                    $initialPreview[] = Html::beginTag('div', ['class' => 'file-preview-other'])
                        . Html::beginTag('span', ['class' => 'file-other-icon'])
                        . Html::tag('i', '', ['class' => 'glyphicon glyphicon-file'])
                        . Html::endTag('span')
                        . Html::endTag('div');
                }
            }
        }

        $files = $this->getFilesByAttributeName($attribute)->all();

        if (is_array($files)) {
            foreach ($files as $file) {
                /** @var File $file */

                if (substr($file->mime, 0, 5) === 'image') {
                    $initialPreview[] = Html::img($file->getUrl($size), ['class' => 'file-preview-image']);
                } else {
                    $initialPreview[] = Html::beginTag('div', ['class' => 'file-preview-other'])
                        . Html::beginTag('span', ['class' => 'file-other-icon'])
                        . Html::tag('i', '', ['class' => 'glyphicon glyphicon-file'])
                        . Html::endTag('span')
                        . Html::endTag('div');
                }
            }
            return $initialPreview;
        }

        return [];
    }

    /**
     * @param string $attribute
     * @return array
     */
    public function getInitialPreviewConfigByAttributeName($attribute = 'file')
    {
        $initialPreviewConfig = [];

        $userTempDir = $this->getModule()->getUserDirPath($attribute);

        if(is_dir($userTempDir)) {
            foreach (FileHelper::findFiles($userTempDir) as $file) {
                $filename = basename($file);
                $initialPreviewConfig[] = [
                    'caption' => $filename,
                    'showDrag' => false,
                    'indicatorNew' => false,
                    'showRemove' => true,
                    'size' => $file->size,
                    'url' => Url::to(['/' . FileModule::getModuleName() . '/file/delete-temp',
                        'filename' => $filename
                    ]),
                ];
            }
        }

        $files = $this->getFilesByAttributeName($attribute)->all();

        if (is_array($files)) {
            foreach ($files as $index => $file) {
                $initialPreviewConfig[] = [
                    'caption' => "$file->name.$file->type",
                    'showDrag' => false,
                    'indicatorNew' => false,
                    'showRemove' => true,
                    'size' => $file->size,
                    'url' => Url::toRoute(['/' . FileModule::getModuleName() . '/file/delete',
                        'id' => $file->id,
                        'item_id' => $this->owner->getAttribute('id'),
                        'model' => $this->getModule()->getClass($this->owner),
                        'attribute' => $attribute
                    ])
                ];
            }
        }

        return $initialPreviewConfig;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getFileCount()
    {
        $count = File::find()
            ->where([
                'item_id' => $this->owner->getAttribute('id'),
                'model' => $this->getModule()->getClass($this->owner)
            ])
            ->count();
        return (int)$count;
    }

    /**
     * @return mixed
     */
    public function getStatsToolbar()
    {
        return StatsToolbarPanels::getDocumentsPanel($this->owner, $this->getFileCount());
    }

    /**
     * @param $attribute
     * @param $csrf
     * @param $dataImage
     * @return bool
     * @throws \Exception
     */
    private function saveFileFormGallery($attribute, $csrf, $dataImage){
        $ok = false;
        foreach ($dataImage as $image) {
            $idGalleryImage = $image['id'];
            $galleryAttribute = $image['attribute'];

            if ($attribute == $galleryAttribute) {
                $imageGallery = AttachGalleryImage::findOne($idGalleryImage);
                if ($imageGallery) {
                    $filePath = $imageGallery->attachImage->getPath();
                    if (file_exists($filePath)) {
                        $this->deleteAttachments($this->owner, $attribute);
                        if ($this->getModule()->attachFile($filePath, $this->owner, $attribute, false)) {
                            $ok = $ok && true;
                        }
                    }
                }
            }
        }

        if ($ok) {
            \Yii::$app->session->remove($csrf);
            return true;
        }

        return false;
    }

}
