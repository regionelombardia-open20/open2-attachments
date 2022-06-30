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

use open20\amos\attachments\models\File;
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