<?php

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
