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

use lispa\amos\attachments\models\File;
use yii\db\ActiveRecord;

/**
 * Class FileActiveRecord
 * @package File\components
 * @property File[] files()
 * @method getInitialPreview()
 * @method getInitialPreviewConfig()
 * @method File[] getFiles()
 */
abstract class FileActiveRecord extends ActiveRecord
{

}