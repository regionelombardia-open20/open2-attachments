<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\attachments
 * @category   CategoryName
 */

namespace lispa\amos\attachments\components;

use lispa\amos\attachments\FileModule;
use lispa\amos\attachments\models\AttachGallery;
use lispa\amos\attachments\models\AttachGalleryCategory;
use lispa\amos\core\icons\AmosIcons;
use lispa\amos\layout\assets\SpinnerWaitAsset;
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

        return "<div class='loading' hidden></div>" .
            \lispa\amos\core\helpers\Html::tag('div',
                \lispa\amos\core\helpers\Html::a(
                    AmosIcons::show('collection-image') . FileModule::t('amosattachments', '#choose_image_from_gallery'), '#', [
                    'class' => 'open-modal-gallery',
                ]),
                ['class' => 'modal-gallery-container']
            )
            . \lispa\amos\core\utilities\ModalUtility::amosModal([
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
        SpinnerWaitAsset::register($this->getView());
        $js = <<<JS
        $('.open-modal-gallery').click(function (event) {
            event.preventDefault();
            $('.loading').show();
           $('#attach-gallery-$attribute > .modal-dialog > .modal-content > .modal-body').load('/attachments/attach-gallery/load-modal?galleryId=$galleryId&attribute=$attribute', function () {
                $('#attach-gallery-$attribute').modal('show');
                $('.loading').hide();
            });
         });
JS;
        $this->getView()->registerJs($js);
    }
}