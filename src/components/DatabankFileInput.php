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


class DatabankFileInput extends Widget
{
    /* @var ActiveForm */
    public $form = null;

    /** @var string */
    public $attribute;


    /** @var array */
    public $options;

    /**
     * @var int
     */
    public $pageSize = 18;

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

        if(!empty($this->attribute)) {
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

            return $loaderHtml .
//                Html::tag('div',
//                    Html::a(
//                        '<span class="mdi mdi-paperclip"></span>' . FileModule::t('amosattachments', 'Seleziona da databank file'), '#', [
//                        'class' => 'open-modal-databank-file btn btn-secondary m-t-5',
//                        'data-attribute' => $attribute,
//                    ]),
//                    ['class' => 'modal-databank-file-container']
//                )
                 ModalUtility::amosModal([
                    'id' => 'attach-databank-file-' . $attribute,
                    'modalBodyContent' => '',
                    'headerText' => FileModule::t('amosattachments', 'Seleziona allegati'),
                    'modalClassSize' => 'modal-lg',
                    'containerOptions' => [
                        'class' => 'modal-utility attachment-databank-file-modal'
                    ],
                    'disableFooter' => true
                ]);
        }
        return '';

    }

    /**
     * @param $galleryId
     * @param $attribute
     */
    public function registerAssets($attribute)
    {
        if (!(!empty(\Yii::$app->params['bsVersion']) && \Yii::$app->params['bsVersion'] == '4.x')){
            SpinnerWaitAsset::register($this->getView());
        }
    $this->view->registerJsVar('loadedOnce', 0);
    $this->view->registerJsVar('pageSize', $this->pageSize);
        $js = <<<JS
        $(document).on('click', '.open-modal-databank-file', function (event) {
            event.preventDefault();
            $('.loading').show();
            var attribute = $(this).attr('data-attribute');
            $('#attach-databank-file-'+attribute+' > .modal-dialog > .modal-content > .modal-body')
            .load('/attachments/attach-databank-file/load-modal?attribute='+attribute+'&pageSize='+pageSize, function () {
                $('#attach-databank-file-'+attribute).modal('show');
                $('.loading').hide();
            });
         });
JS;
        $this->getView()->registerJs($js);
    }
}