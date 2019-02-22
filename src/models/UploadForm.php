<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\attachments
 * @category   CategoryName
 */

namespace lispa\amos\attachments\models;

use lispa\amos\attachments\FileModuleTrait;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * Class UploadForm
 * @package lispa\amos\attachments\models
 */
class UploadForm extends Model
{
    use FileModuleTrait;

    /**
     * @var UploadedFile[]|UploadedFile file attribute
     */
    public $file;

    /**
     * @var ActiveRecord
     */
    public $modelSpecific;

    /**
     * @var string
     */
    public $attributeSpecific;

    /**
     * @return bool
     *
     * public function beforeValidate()
     * {
     * $attributeValidators = $this->modelSpecific->getActiveValidators($this->attributeSpecific);
     *
     * foreach($attributeValidators as $validator) {
     * $validator->attributes = ['file'];
     * $this->validators->append($validator);
     * //$this->activeValidators[] = $validator;
     * }
     *
     * return parent::beforeValidate();
     * }*/
}