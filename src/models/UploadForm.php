<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments
 * @category   CategoryName
 */

namespace open20\amos\attachments\models;

use open20\amos\attachments\FileModuleTrait;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * Class UploadForm
 * @package open20\amos\attachments\models
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