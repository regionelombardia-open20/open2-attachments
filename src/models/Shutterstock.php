<?php

namespace open20\amos\attachments\models;

use open20\amos\attachments\FileModule;
use open20\amos\attachments\models\AttachGalleryImage;
use open20\amos\attachments\utility\AttachmentsUtility;
use open20\amos\attachments\utility\ShutterstockClient;
use open20\amos\core\helpers\Html;
use open20\amos\utility\models\PlatformConfigs;
use yii\base\ErrorException;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\db\Exception;

class Shutterstock extends Model
{
    public $query;
    public $sort;
    public $image_type;
    public $orientation;
    public $color;

    public $pagination;
    public $errorMessage;

    const ORDER_NEWEST = 'newest';
    const ORDER_POPULAR = 'popular';
    const ORDER_RANDOM = 'random';
    const ORDER_RELEVANCE = 'relevance';

    const ORIENTATION_HORIZONTAL = 'horizontal';
    const ORIENTATION_VERTICAL = 'vertical';

    const IMAGE_TYPE_PHOTO = 'photo';
    const IMAGE_TYPE_ILLUSTRATION = 'illustration';
    const IMAGE_TYPE_VECTOR = 'vector';


    /**
     * @return array[]
     */
    public function rules()
    {

        return [
            [['query', 'sort', 'image_type', 'orientation', 'color'], 'safe']
        ];
    }

    /**
     * @return array
     */
    public static function orderTypes()
    {
        return [
            self::ORDER_POPULAR => FileModule::t('amoattachments', "Popolari"),
            self::ORDER_NEWEST => FileModule::t('amoattachments', "Contenuti nuovi"),
            self::ORDER_RELEVANCE => FileModule::t('amoattachments', "Più pertinenti"),
            self::ORDER_RANDOM => FileModule::t('amoattachments', "Casuali"),
        ];
    }

    /**
     * @return array
     */
    public static function imageTypes()
    {
        return [
            self::IMAGE_TYPE_PHOTO => FileModule::t('amoattachments', "Foto"),
            self::IMAGE_TYPE_ILLUSTRATION => FileModule::t('amoattachments', "Illutrazioni"),
            self::IMAGE_TYPE_VECTOR => FileModule::t('amoattachments', "Immagini vettoriali"),
        ];
    }

    /**
     * @return array
     */
    public static function orientationTypes()
    {
        return [
            self::ORIENTATION_HORIZONTAL => FileModule::t('amoattachments', "Orizzontale"),
            self::ORIENTATION_VERTICAL => FileModule::t('amoattachments', "Verticale"),
        ];
    }

    /**
     * @param $params
     * @return ArrayDataProvider|null
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function search($params)
    {
        $shutterstock = new ShutterstockClient();
        $dataProvider = new ArrayDataProvider(['allModels' => []]);
        if ($this->load($params)) {
            $result = $shutterstock->searchImages($this->formatParamsForApi());
            if (!empty($shutterstock->errorMessage)) {
                $this->errorMessage = $shutterstock->errorMessage;
            }
            if (!empty($result->data)) {
//                pr($result->data);
                $dataProvider = new ArrayDataProvider(['allModels' => $result->data]);
                $this->pagination = $shutterstock->getPagination($result);
//                $dataProvider->pagination = $pagination;
//                foreach($result->data as $image){
//                    $image
//                    pr($image);
//                }
            }
//            pr($images);
        }
        return $dataProvider;
    }

    /**
     * @return array
     */
    public function formatParamsForApi()
    {
        $params = [];
        $page = \Yii::$app->request->get('page');

        $params['query'] = $this->query;
        if (!empty($this->sort)) {
            $params['sort'] = $this->sort;
        }
        if (!empty($this->color)) {
            $params['color'] = str_replace('#', '', $this->color);
        }
        if (!empty($this->image_type)) {
            $params['image_type'] = str_replace('#', '', $this->image_type);
        }


        if (!empty($this->orientation)) {
            $params['orientation'] = $this->orientation;
        }
        if (!empty($page)) {
            $params['page'] = $page;
        }
        return $params;
    }

    /**
     * @param $model
     * @param $thumb_type
     * @return string
     */
    public static function getRenderedImage($model, $thumb_type = 'preview')
    {
        if ($thumb_type == 'small_thumb' || $thumb_type == 'large_thumb') {
            return Html::tag('div', Html::img($model->assets->$thumb_type->url), [
            ]);
        } else {
            $height = $model->assets->$thumb_type->height;
            $width = $model->assets->$thumb_type->width;
            return Html::tag('div', Html::img($model->assets->$thumb_type->url), [
                'style' => "height:{$height}px;width:{$width}px;overflow:hidden"
            ]);
        }
    }

    /**
     * @param $model
     * @param $thumb_type
     * @return string
     */
    public static function getUrlImagePreview($model, $thumb_type = 'preview')
    {
        if (!empty($model->assets->$thumb_type)) {
            return $model->assets->$thumb_type->url;
        }
        return '';
    }

    /**
     * @param $model
     * @return string
     */
    public static function formatCategories($model)
    {
        $tmp = '';
        if (!empty($model->categories)) {
            foreach ($model->categories as $category) {
                $tmp .= Html::tag(
                    'span',
                    $category->name,
                    [
                        'class' => 'label label-default'
                    ]);
            }
        }
        return $tmp;
    }

    /**
     * @param $model
     * @return string
     */
    public static function formatKeywords($model)
    {
        $tmp = '';
        if (!empty($model->keywords)) {
            foreach ($model->keywords as $keyword) {
                $tmp .= Html::tag(
                    'span',
                    $keyword,
                    [
                        'class' => 'label label-default'
                    ]);
            }
        }
        return $tmp;
    }

    /**
     * @param $model
     * @return string
     */
    public static function labelSizeFormat($model, $withSize = false)
    {
        if ($model->display_name) {
            if ($withSize) {
                $size = ' - ' . $model->width . 'x' . $model->height;
            }

            switch ($model->display_name) {
                case 'Small':
                    return FileModule::t('amosattchments', 'Piccolo') . $size;
                case 'Med':
                    return FileModule::t('amosattchments', 'Medio') . $size;
                case 'Huge':
                    return FileModule::t('amosattchments', 'Grande') . $size;
                case 'Vector':
                    return FileModule::t('amosattchments', 'Immagine vettoriale') . $size;
            }
        }
        return '';
    }

    /**
     * @return null
     */
    public static function getSubscriptionId()
    {
        $shutterstock = new ShutterstockClient();
        $subscription = $shutterstock->getSubscriptions();
        self::saveDownloadsLeft($subscription);
        if (!empty($subscription->data)) {
            $firstSubscription = $subscription->data[0];
            return $firstSubscription->id;
        }
        return null;
    }

    /**
     * @return null
     */
    public static function getSubscriptionDownloadRemainingFromApi()
    {
        $shutterstock = new ShutterstockClient();
        $subscription = $shutterstock->getSubscriptions();
        $config = self::saveDownloadsLeft($subscription);
        if ($config) {
            return $config->value;
        }
        return null;
    }

    /**
     * @return mixed|string|null
     */
    public static function getDownloadRemainingFromDb()
    {

        $expireDate = AttachmentsUtility::getPlatformConfig(ShutterstockClient::PLATFORM_CONFIGS_DOWNLOADS_EXPIRE);
        if ($expireDate) {
            $now = new \DateTime();
            $expire = new \DateTime($expireDate);
            if ($now > $expire) {
                return self::getSubscriptionDownloadRemainingFromApi();
            }
        //se ancora non è stato configurato il parametro
        } else {
            return self::getSubscriptionDownloadRemainingFromApi();
        }
        $downloadLeft = AttachmentsUtility::getPlatformConfig(ShutterstockClient::PLATFORM_CONFIGS_DOWNLOADS_LEFT);
        return $downloadLeft;
    }

    /**
     * @param $model
     * @return array|PlatformConfigs|\yii\db\ActiveRecord|null
     */
    public static function saveDownloadsLeft($model)
    {
        $config = null;
        if (!empty($model->data)) {
            $subscription = $model->data[0];
            if ($subscription) {
                $left = $subscription->allotment->downloads_left;
                $limit = $subscription->allotment->downloads_limit;
                $end = $subscription->allotment->end_time;
                $dateEnd = new \DateTime($end);
//                pr($left . '/' . $limit);
//                pr($left . '/' . $limit);
//                pr($left . '/' . $limit);
                AttachmentsUtility::savePlatformConfig(ShutterstockClient::PLATFORM_CONFIGS_DOWNLOADS_EXPIRE, $dateEnd->format('Y-m-d H:i:s'));
                $config = AttachmentsUtility::savePlatformConfig(ShutterstockClient::PLATFORM_CONFIGS_DOWNLOADS_LEFT, $left . '/' . $limit);
            }
        }
        return $config;
    }

    /**
     * @return bool
     */
    public static function canDownloadImage(){
        /** @var  $module FileModule*/
        $module = \Yii::$app->getModule('attachments');
        if($module && !empty($module->shutterstockConfigs['enableSandbox'])){
            return true;
        }

        $downloadLeft = self::getDownloadRemainingFromDb();
        $explode = explode('/',$downloadLeft);
        if(count($explode) == 2){
            $left = $explode[0];
            if($left == 0){
                return false;
            }
        }
        return true;
    }

    public static function licensableImages($type)
    {
        switch ($type) {
            case 'vector_eps':
                return 'vector';
            case 'small_jpg':
                return 'small';
            case 'medium_jpg';
                return 'medium';
            case 'huge_jpg':
                return 'huge';
        }
        return 'huge';
    }

    /**
     * @param $images
     * @return array
     */
    public static function formatLincensableImagesForSelect($images)
    {
        $data = [];
        foreach ($images as $type => $image) {
            $data[self::licensableImages($type)] = self::labelSizeFormat($image, true);
        }
        return $data;
    }


    /**
     * @param $id_image
     * @param $attribute
     * @param $proscriptions
     * @param $isBought
     * @param $name
     * @param $module
     * @param $csrf
     * @return array|int
     */
    public static function saveAttachmentFromShutterstock($id_image, $attribute, $activeProscription, $isBought, $name, $module, $csrf)
    {

        $message = '';
        $dataError = '';
        try {
            if (!empty($activeProscription)) {
                $proscription = $activeProscription;
                $shutterstock = new ShutterstockClient();

                //se non è stata appena creata la licenza, recupero l'url tramite l'id licenza
                if (!$isBought) {
                    if (!empty($proscription->image->format->size)) {
                        $downloadUrl = $shutterstock->downloadImage($proscription->id, ['size' => $proscription->image->format->size]);
                        $message = $shutterstock->errorMessage;
//                        $message .= $proscription->id . ' ' . $downloadUrl;
                    } else {
                        $message .= "Licenza per l'immagine $id_image non valida";
                    }
                } else {
                    $downloadUrl = $proscription->download->url;
                }
                //altrimenti se stata appena comprata ho direttmaente l'url
                if (!empty($downloadUrl)) {
//                    // download image from url and save tempFile
                    $explode = explode('/', $downloadUrl);
                    $filename = 'shutterstock_' . $id_image . '.jpg';
                    if (!empty($explode)) {
                        $filename = end($explode);
                    }
                    $img = $module->getTempPath() . '/' . $filename;

                    //save file
                    file_put_contents($img, file_get_contents($downloadUrl));

                    //create record in databank images
                    $galleryImage = AttachGalleryImage::find()->andWhere(['shutterstock_image_id' => $id_image])->one();
                    if (empty($galleryImage)) {
                        list($width, $height, $type, $attr) = getimagesize($img);
                        $detailImage = $shutterstock->searchImageDetail($id_image, ['view' => 'full']);

                        $galleryImage = new AttachGalleryImage();
                        $galleryImage->name = !empty($model->description) ? $model->description : $name;
                        $galleryImage->shutterstock_image_id = $id_image;
                        $galleryImage->gallery_id = 1;
                        $galleryImage->aspect_ratio = round($width / $height, 1);
                        $galleryImage->save(false);
                        //attach the file to the gallery
                        $module->attachFile($img, $galleryImage, 'attachImage', true, false);
                        $galleryImage->saveOnLuya();

                        //salva parole chiave nei tag liberi
                        if ($detailImage) {
                            $keywords = implode(',', $detailImage->keywords);
                            $galleryImage->customTags = $keywords;
                            $galleryImage->saveCustomTags();
                        }
                    }
                    if ($galleryImage) {
                        //save in session param to attach file to the content ( for example News )
                        return $galleryImage;

                    }
                }
            }
            return ['error' => $message, 'dataError' => $dataError];
        } catch (ErrorException $e) {
            return ['error' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'stack' => $e->getTraceAsString()];

        } catch (\Error $e) {
            return ['error' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'stack' => $e->getTraceAsString()];

        } catch (\Exception $e) {
            return ['error' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'stack' => $e->getTraceAsString()];

        } catch (Exception $e) {
            return ['error' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'stack' => $e->getTraceAsString()];

        }
    }

    /**
     * @param $image_id
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public static function isImageInAttachGallery($image_id)
    {
        $count = AttachGalleryImage::find()
            ->andWhere(['shutterstock_image_id' => $image_id])->count();
        return $count > 0;
    }

    /**
     * @param $proscriptions
     * @param $format_type
     * @return mixed|null
     */
    public static function activeProscriptionForImage($proscriptions, $format_type)
    {
        foreach ($proscriptions->data as $proscription) {
            if ($proscription->image->format->size == $format_type && $proscription->is_downloadable) {
                return $proscription;
            }
        }
        return null;
    }

    /**
     * @param $model Image
     * @return void
     */
    public static function contributorName($model)
    {
        $shutterstock = new ShutterstockClient();
        if (!empty($model->contributor)) {
            $contributor = $shutterstock->getContributor($model->contributor->id);
            if (!empty($contributor->display_name)) {
                return $contributor->display_name;
            }
        }
        return null;
    }

}