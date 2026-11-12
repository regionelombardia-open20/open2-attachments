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

namespace open20\amos\attachments\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "admin_storage_file".
 */
class AdminStorageFile extends \open20\amos\attachments\models\base\AdminStorageFile
{
    public function representingColumn()
    {
        return [
//inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints()
    {
        return [
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
        ]);
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                ]);
    }


    public function getEditFields()
    {
        $labels = $this->attributeLabels();

        return [
            [
                'slug' => 'is_hidden',
                'label' => $labels['is_hidden'],
                'type' => 'tinyint'
            ],
            [
                'slug' => 'folder_id',
                'label' => $labels['folder_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'name_original',
                'label' => $labels['name_original'],
                'type' => 'string'
            ],
            [
                'slug' => 'name_new',
                'label' => $labels['name_new'],
                'type' => 'string'
            ],
            [
                'slug' => 'name_new_compound',
                'label' => $labels['name_new_compound'],
                'type' => 'string'
            ],
            [
                'slug' => 'mime_type',
                'label' => $labels['mime_type'],
                'type' => 'string'
            ],
            [
                'slug' => 'extension',
                'label' => $labels['extension'],
                'type' => 'string'
            ],
            [
                'slug' => 'hash_file',
                'label' => $labels['hash_file'],
                'type' => 'string'
            ],
            [
                'slug' => 'hash_name',
                'label' => $labels['hash_name'],
                'type' => 'string'
            ],
            [
                'slug' => 'upload_timestamp',
                'label' => $labels['upload_timestamp'],
                'type' => 'integer'
            ],
            [
                'slug' => 'file_size',
                'label' => $labels['file_size'],
                'type' => 'integer'
            ],
            [
                'slug' => 'upload_user_id',
                'label' => $labels['upload_user_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'is_deleted',
                'label' => $labels['is_deleted'],
                'type' => 'tinyint'
            ],
            [
                'slug' => 'passthrough_file',
                'label' => $labels['passthrough_file'],
                'type' => 'tinyint'
            ],
            [
                'slug' => 'passthrough_file_password',
                'label' => $labels['passthrough_file_password'],
                'type' => 'string'
            ],
            [
                'slug' => 'passthrough_file_stats',
                'label' => $labels['passthrough_file_stats'],
                'type' => 'integer'
            ],
            [
                'slug' => 'caption',
                'label' => $labels['caption'],
                'type' => 'text'
            ],
            [
                'slug' => 'inline_disposition',
                'label' => $labels['inline_disposition'],
                'type' => 'tinyint'
            ],
        ];
    }

    /**
     * @return string marker path
     */
    public function getIconMarker()
    {
        return null; //TODO
    }

    /**
     * If events are more than one, set 'array' => true in the calendarView in the index.
     * @return array events
     */
    public function getEvents()
    {
        return NULL; //TODO
    }

    /**
     * @return url event (calendar of activities)
     */
    public function getUrlEvent()
    {
        return NULL; //TODO e.g. Yii::$app->urlManager->createUrl([]);
    }

    /**
     * @return color event
     */
    public function getColorEvent()
    {
        return NULL; //TODO
    }

    /**
     * @return title event
     */
    public function getTitleEvent()
    {
        return NULL; //TODO
    }

    public function deleteCms()
    {
        $this->is_hidden = true;
        $this->detachBehavioursForSave();
        $this->save(false);
    }

    public function saveCms(){
        $this->detachBehavioursForSave();
        $this->save(false);
    }

    public function detachBehavioursForSave(){
        $this->detachBehavior('SoftDeleteByBehavior');
        $this->detachBehavior('TimestampBehavior');
        $this->detachBehavior('BlameableBehavior');
    }


}
