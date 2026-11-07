<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\models
 */

namespace open20\amos\attachments\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "attach_gallery".
 */
class AttachGallery
    extends \open20\amos\attachments\models\base\AttachGallery
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'slug' => [
                    'class' => SluggableBehavior::class,
                    'attribute' => 'name',
                    'ensureUnique' => true
                ],
            ]
        );
    }

    /**
     *
     * @return type
     */
    public function attributeHints()
    {
        return [];
    }

    /**
     * @see\open20\amos\core\record\Record::representingColumn() or more info.
     */
    public function representingColumn()
    {
        return [
            'name'
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

    /**
     *
     */
    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            []
        );
    }

    /**
     *
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(
            parent::attributeLabels(),
            []
        );
    }

    /**
     *
     */
    public function getEditFields()
    {
        $labels = $this->attributeLabels();

        return [
            [
                'slug' => 'slug',
                'label' => $labels['slug'],
                'type' => 'string'
            ],
            [
                'slug' => 'name',
                'label' => $labels['name'],
                'type' => 'string'
            ],
            [
                'slug' => 'description',
                'label' => $labels['description'],
                'type' => 'text'
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
        return null; //TODO
    }

    /**
     * @return url event (calendar of activities)
     */
    public function getUrlEvent()
    {
        return null; //TODO e.g. Yii::$app->urlManager->createUrl([]);
    }

    /**
     * @return color event
     */
    public function getColorEvent()
    {
        return null; //TODO
    }

    /**
     * @return title event
     */
    public function getTitleEvent()
    {
        return null; //TODO
    }

    /**
     *
     */
    public function getFullViewUrl()
    {
        return '/attachments/attach-gallery-image?id=' . $this->id;
    }



}
