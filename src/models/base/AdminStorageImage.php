<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-attachments
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

namespace open20\amos\attachments\models\base;

use Yii;

/**
* This is the base-model class for table "admin_storage_image".
*
    * @property integer $id
    * @property integer $file_id
    * @property integer $filter_id
    * @property integer $resolution_width
    * @property integer $resolution_height
*/
 class  AdminStorageImage extends \open20\amos\core\record\Record
{
    public $isSearch = false;

/**
* @inheritdoc
*/
public static function tableName()
{
return 'admin_storage_image';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['file_id', 'filter_id', 'resolution_width', 'resolution_height'], 'integer'],
];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => Yii::t('app', 'ID'),
    'file_id' => Yii::t('app', 'File ID'),
    'filter_id' => Yii::t('app', 'Filter ID'),
    'resolution_width' => Yii::t('app', 'Resolution Width'),
    'resolution_height' => Yii::t('app', 'Resolution Height'),
];
}
}
