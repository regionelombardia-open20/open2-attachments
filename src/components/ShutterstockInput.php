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
use open20\amos\core\helpers\Html;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\layout\assets\SpinnerWaitAsset;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\base\Widget;
use yii\widgets\InputWidget;
use yii\widgets\ActiveForm;


class ShutterstockInput extends Widget
{
    /* @var ActiveForm */
    public $form = null;

    /** @var string */
    public $attribute;


    /** @var array */
    public $options;

    public $aspectRatio;


    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Renders the field.
     */
    public function run()
    {
        $attribute = $this->attribute;

        $this->registerAssets($attribute);
        $loaderHtml =
            '<div class="dimmable position-fixed loader loading" style="display:none">
            <div class="dimmer d-flex align-items-center" id="dimmer1">
                <div class="dimmer-inner">
                    <div class="dimmer-icon">
                        <div class="progress-spinner progress-spinner-active loading m-auto">
                            <span class="sr-only">Caricamento...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>';

        $hiddenInput = Html::hiddenInput('shutterstock_file_is_selected_' . $attribute, 0,['id' => 'id-shutterstock_file_is_selected_'.$attribute]);
        return
            ModalUtility::amosModal([
                'id' => 'attach-shutterstock-' . $attribute,
                'modalBodyContent' => '',
                'modalClassSize' => 'modal-lg',
                'headerText' => FileModule::t('amosattachments', 'Seleziona immagine'),
                'containerOptions' => [
                    'class' => 'modal-utility attachment-shutterstock-modal'
                ]
            ]) . $loaderHtml.$hiddenInput;
    }

    /**
     * @param $galleryId
     * @param $attribute
     */
    public function registerAssets($attribute)
    {
        if (!(!empty(\Yii::$app->params['bsVersion']) && \Yii::$app->params['bsVersion'] == '4.x')) {
            SpinnerWaitAsset::register($this->getView());
        }

        $this->view->registerJsVar('loadedOnce', 0);
        $this->view->registerJsVar('aspectRatio'.$attribute, $this->aspectRatio);

        $js = <<<JS
        $(document).on('click', '.open-modal-shutterstock', function (event) {
            event.preventDefault();
            $('.loading').show();
            var attribute = $(this).attr('data-attribute');
            $('#attach-shutterstock-'+attribute+' > .modal-dialog > .modal-content > .modal-body').load('/attachments/shutterstock/load-modal?attribute='+attribute, function () {
                $('#attach-shutterstock-'+attribute).modal('show');
                $('.loading').hide();
            });
         });
JS;
        $this->getView()->registerJs($js);
    }
}