<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments
 * @category   CategoryName
 */

namespace open20\amos\attachments\components;

use open20\amos\attachments\FileModule;
use open20\amos\attachments\models\AttachGallery;
use open20\amos\attachments\models\AttachGalleryCategory;
use open20\amos\core\icons\AmosIcons;
use open20\amos\layout\assets\SpinnerWaitAsset;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\widgets\InputWidget;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


class GalleryInput extends Widget
{
    /* @var ActiveForm */
    public $form = NULL;

    /** @var string */
    public $attribute;

    /** @var string */
    public $gallery = 'general';

    /** @var array */
    public $options;


    /** @var $modelGallery AttachGallery */
    private $modelGallery;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        $this->modelGallery = AttachGallery::findOne(['slug' => $this->gallery]);
        if (empty($this->attribute)) {
            throw new InvalidConfigException("The field attribute is required.");
        }
        if (empty($this->modelGallery)) {
            throw new InvalidConfigException("The gallery is not found, check if the slug of the gallery exists");
        }
        parent::init();
    }

    /**
     * Renders the field.
     */
    public function run()
    {

        $galleryId = $this->modelGallery->id;
        $attribute = $this->attribute;

        $this->registerAssets($galleryId, $attribute);
        $loaderHtml =
        "<div class=\"dimmable position-fixed loader loading\" style=\"display:none\">
            <div class=\"dimmer d-flex align-items-center\" id=\"dimmer1\">
                <div class=\"dimmer-inner\">
                    <div class=\"dimmer-icon\">
                        <div class=\"progress-spinner progress-spinner-active loading m-auto\">
                            <span class=\"sr-only\">Caricamento...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>";

        return $loaderHtml .
            \open20\amos\core\helpers\Html::tag('div',
                \open20\amos\core\helpers\Html::a(
                    AmosIcons::show('collection-image') . FileModule::t('amosattachments', 'Carica da databank immagini'), '#', [
                    'class' => 'open-modal-gallery',
                    'data-attribute' => $attribute,
                    'data-gallery' => $galleryId,
                ]),
                ['class' => 'modal-gallery-container']
            )
            . \open20\amos\core\utilities\ModalUtility::amosModal([
                'id' => 'attach-gallery-' . $attribute,
                'modalBodyContent' => '',
                'modalClassSize' => 'modal-lg',
                'containerOptions' => [
                    'class' => 'modal-utility attachment-gallery-modal'
                ]
            ]);
    }


    /**
     * @param $galleryId
     * @param $attribute
     */
    public function registerAssets($galleryId, $attribute)
    {
        if (!(!empty(\Yii::$app->params['bsVersion']) && \Yii::$app->params['bsVersion'] == '4.x')){
            SpinnerWaitAsset::register($this->getView());
        }

        $js = <<<JS
        $(document).on('click', '.open-modal-gallery', function (event) {
            event.preventDefault();
            $('.loading').show();
            var attribute = $(this).attr('data-attribute');
            var gallery_id = $(this).attr('data-gallery');
           $('#attach-gallery-'+attribute+' > .modal-dialog > .modal-content > .modal-body').load('/attachments/attach-gallery/load-modal?galleryId='+gallery_id+'&attribute='+attribute, function () {
                $('#attach-gallery-'+attribute).modal('show');
                $('.loading').hide();
            });
         });
JS;
        $this->getView()->registerJs($js);
    }
}