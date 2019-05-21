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

    /** @var array */
    var $jcropOptions = [];

    /** @var integer */
    var $maxSize = 300;

    /** @var string */
    var $defaultPreviewImage = NULL;

    /** @var boolean */
    var $enableUploadFromGallery = true;

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
        }
        if ((property_exists($this->model, $this->attribute) || isset($this->model->{$this->attribute})) && $this->model->{$this->attribute}) {
            if (is_null($this->defaultPreviewImage)) {
                $this->defaultPreviewImage = $this->model->{$this->attribute}->url;
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

        $this->imageOptions['id'] = Yii::$app->getSecurity()->generateRandomString(10);

        $inputField = Html::getInputId($this->model, $this->attribute, ['data-image_id' => $this->imageOptions['id']]);

        $default_jcropOptions = [
            'aspectRatio' => 1,
            'viewMode' => 2,
            'dashed' => FALSE,
            'zoomable' => FALSE,
            'rotatable' => FALSE
        ];

        $this->jcropOptions = array_merge($default_jcropOptions, $this->jcropOptions);

        return $this->render('crop-view', [
        'inputField' => $inputField,
        'crop' => $this
        ]);
    }
}