<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\attachments
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
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
use yii\helpers\Json;
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
        $this->view->registerJsVar('selected_file_ids', []);
        $this->view->registerJsVar('selected_file_names', []);
        $this->view->registerJsVar('loadedOnce', []);
        $this->view->registerJsVar('loadedStaticOnce', 0);
        $this->view->registerJsVar('pageSize', $this->pageSize);

        $extensions = ($this->options['extensions'] === '') ? '' : Json::encode($this->options['extensions']);

        $js = <<<JS
        $(document).on('click', '.open-modal-databank-file.$attribute', function (event) {
            event.preventDefault();
            $('.loading').show();
            var attribute = $(this).attr('data-attribute');
            var extensions = '{$extensions}';
            var link = '/attachments/attach-databank-file/load-modal?attribute='+attribute+'&pageSize='+pageSize;
            if(extensions != '') {
                link = link + '&extensions=' + extensions;
            }
            $('#attach-databank-file-'+attribute+' > .modal-dialog > .modal-content > .modal-body')
            .load(link, function () {
                $('#attach-databank-file-'+attribute).modal('show');
                $('.loading').hide();
            });
         });
JS;
        $this->getView()->registerJs($js);
    }
}