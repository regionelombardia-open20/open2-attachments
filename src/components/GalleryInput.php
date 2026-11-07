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


class GalleryInput extends Widget
{
    /* @var ActiveForm */
    public $form = null;

    /** @var string */
    public $attribute;

    /** @var string */
    public $gallery = 'general';

    /** @var array */
    public $options;
    
    /** @var boolean */
    public $multiSelection = false;

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

        $footer = "<div class='col-md-6 text-left'>
            <div class='dropdown'>
                <button id='label-selezionati-$attribute' type='button' data-toggle='dropdown' class='btn btn-link' aria-haspopup='true' aria-expanded='false'>
                    ".FileModule::t('amosattachments', 'Immagini selezionate')."
                    (<span class='count'>0</span>)
                    <span class='caret'></span>
                </button>
                <ul id='selected-files-$attribute' class='dropdown-menu' aria-labelledby='dLabel' style='padding:6px 14px'>
                </ul>
            </div>
        </div>
        <div class='col-md-6 text-right'>
            <button type='button' class='btn btn-secondary' data-dismiss='modal'>".FileModule::t('amosattachments', 'Chiudi')."</button>
            <a href='#' id='insert-images-btn-$attribute' class='btn btn-primary pull-right' title='".FileModule::t('amosattachments', 'Inserisci immagine')."'><span class='mdi mdi-loading mdi-spin hide' style='margin-right:10px'></span> ".FileModule::t('amosattachments', 'Inserisci')."</a>
        </div>";
        
        return
            ModalUtility::amosModal([
                'id' => 'attach-gallery-' . $attribute,
                'modalBodyContent' => '',
                'modalClassSize' => 'modal-lg',
                'headerText' => FileModule::t('amosattachments', 'Seleziona immagine'),
                'disableFooter' => !$this->multiSelection,
                'footerText' => $footer,
                'containerOptions' => [
                    'class' => 'modal-utility attachment-gallery-modal'
                ]
            ]).$loaderHtml;
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
        $(document).on('click', '.open-modal-gallery-$attribute', function (event) {
            event.preventDefault();
            $('.loading').show();
            var attribute = $(this).attr('data-attribute');              
            var gallery_id = $(this).attr('data-gallery');
            
            $('#label-selezionati-$attribute .count').html(0);
            $('#selected-files-$attribute').html('');
            $('#multiuploads-alt-descriptions-gallery-$attribute').html('');
            $('#insert-images-btn-$attribute').removeClass('disabled');
            $('#insert-images-btn-$attribute').find('.mdi-loading').addClass('hide');
   
            $('#attach-gallery-'+attribute+' > .modal-dialog > .modal-content > .modal-body').load('/attachments/attach-gallery/load-modal?galleryId='+gallery_id+'&attribute='+attribute+'&multiSelection={$this->multiSelection}', function () {
                $('#attach-gallery-'+attribute).modal('show');
                $('.loading').hide();
            });
            
         });
            
        $('#attach-gallery-$attribute').on('hidden.bs.modal', function () {
            $(this).find('.modal-body').empty();
        });
JS;
        $this->getView()->registerJs($js);
    }
}