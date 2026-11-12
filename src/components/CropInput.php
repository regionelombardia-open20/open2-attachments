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
use yii\widgets\InputWidget;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

class CropInput extends InputWidget
{
    /* @var ActiveForm */
    var $form = NULL;

    /** @var boolean */
    var $enableClientValidation;

    /** @var array */
    var $imageOptions;

    /**
     * Example:
     *   [
     *       [
     *           'label' => 'X',
     *           'value' => 'x',
     *           'title' => 'Elimina il crop',
     *       ],
     *       [
     *           'label' => '1:1',
     *          'value' => 1,
     *           'title' => 'Fattore di crop 1:1',
     *       ],
     *       [
     *           'label' => '19:9',
     *           'value' => (16/9),
     *           'title' => 'Fattore di crop 16:9',
     *       ],
     *       [
     *           'label' => 'free',
     *           'value' => 'NaN',
     *           'title' => 'Fattore di crop libero',
     *       ],
     *   ],
     *  @var array */
    var $aspectRatioChoices;

    /** @var array */
    var $jcropOptions = [];

    /** @var integer */
    var $maxSize = 300;

    /** @var string */
    var $defaultPreviewImage = null;

    /** @var string */
    var $defaultPreviewImageAlt = null;


    /** @var string */
    var $customHint = null;

    /** @var boolean */
    var $enableUploadFromGallery = true;

    /** @var boolean */
    var $enableUploadFromShutterstock = true;

    /**
     * Custom label for upload button
     * @var string
     */
    var $uploadBtnLabel = null;

    /**
     *
     * @var boolean
     */
    var $isFrontend = false;

    /**
     * @param boolean  for enable hint message
     */
    var $enableHintMessage = true;

    /** @var array */
    var $cropModalCloseButton = [];

    /**
     * @var bool
     */
    var $hidePreviewDeleteButton = false;
    
    /**
     * @var bool
     */
    var $enableMultipleUploads = false;
    /**
     * @var integer
     * used to limit to upload more then 10 imaged at a time
     */
    var $maxMultipleUploads = 10; 
    
    /**
     * @var bool
     */
    var $enableAltDescription = false;
    /**
     * @var bool
     * used in single image upload
     * auto disabled if enableMultipleUploads = true
     */
    var $enableUpdateAltDescription = false;

    /**
     * only call this method after a form closing and
     *    when user hasn't used in the widget call the parameter $form
     *    this adds to every form in the view the field validation.
     *
     * @param array $config
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    static function manualValidation($config = [])
    {

        if (!array_key_exists('model', $config) || !array_key_exists('attribute', $config)) {
            throw new InvalidParamException('Config array must have a model and attribute.');
        }

        $view = Yii::$app->getView();
        $field_id = Html::getInputId($config['model'], $config['attribute']);
        $view->registerJs('$("#' . $field_id . '").urlParser("launchValidation");');
    }

    /**
     * Renders the field.
     */
    public function run()
    {
        $fileModule = \Yii::$app->getModule('attachments');
        if($fileModule){
            if($fileModule->disableGallery){
                $this->enableUploadFromGallery = false;
            }
            if(!isset($fileModule->shutterstockConfigs['enable']) || (isset($fileModule->shutterstockConfigs['enable']) && $fileModule->shutterstockConfigs['enable'] !== true)){
                $this->enableUploadFromShutterstock = false;
            }
        }

        $oldGetAttribute = 'get'.ucfirst($this->attribute);      
        if ((property_exists($this->model, $this->attribute) || isset($this->model->{$this->attribute})) && ($this->model->{$this->attribute} || method_exists($this->model,$oldGetAttribute))) {
             
            if (is_null($this->defaultPreviewImage)) {
                $this->defaultPreviewImage = $this->model->{$this->attribute}->url;
            }

            if (is_null($this->defaultPreviewImageAlt)) {
                $this->defaultPreviewImageAlt = FileModule::t('amosattachments', 'Anteprima per: ') . ' ' . $this->model->getAttributeLabel($this->attribute);
            }

            if (empty($this->jcropOptions)) {
                $this->jcropOptions = [
                    'aspectRatio' => 1,
                    'viewMode' => 3
                ];
            }
        }

        //Pick parent form
        if (is_null($this->form)) {
            $this->form = $this->field->form;
        }

        if (is_null($this->imageOptions)) {
            $this->imageOptions = [
                'alt' => 'Crop this image'
            ];
        }
        
        if($this->enableMultipleUploads){
            $this->enableUpdateAltDescription = false;
        }
        
        if(!$this->enableMultipleUploads){
            if(isset($this->options) && isset($this->options['multiple']))
                $this->options['multiple'] = false;       
        }

        $this->imageOptions['id'] = Yii::$app->getSecurity()->generateRandomString(10);

        $inputField = (isset($this->options['id']) && (!empty($this->options['id'])))? $this->options['id']: Html::getInputId($this->model, $this->attribute, ['data-image_id' => $this->imageOptions['id']]);

        $default_jcropOptions = [
            'aspectRatio' => 1,
            'viewMode' => 2,
            'dashed' => false,
            'zoomable' => false,
            'rotatable' => true
        ];
        $default_options = [
            'accept' => "image/*",
            'aria-label' => 'Seleziona file',
        ];

        $this->jcropOptions = array_merge($default_jcropOptions, $this->jcropOptions);
        $this->options = array_merge($default_options, $this->options);
       
        return $this->render('crop-view', [
            'inputField' => $inputField,
            'crop' => $this,
            'aspectRatio' => $this->jcropOptions['aspectRatio'],
            'customHint'=> $this->customHint,
            'aspectRatioChoices' => $this->aspectRatioChoices,
            'cropModalCloseButton' => $this->cropModalCloseButton,
            'uploadBtnLabel' => $this->uploadBtnLabel,
        ]);
    }
}