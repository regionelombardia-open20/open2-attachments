<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\utility
 */

namespace open20\amos\attachments\utility;

use open20\amos\attachments\FileModule;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;

use yii\helpers\ArrayHelper;

/**
 * 
 */
class AttachmentsUtility
{
    /**
     * @param $ratio
     * @return string
     */
    public static function getFormattedAspectRatio($ratio)
    {
        $formattedRatio = 'other';
        switch ($ratio) {
            case '1':
                $formattedRatio = '1:1';
                break;
            case '1.7':
                $formattedRatio = '16:9';
                break;
        }

        return $formattedRatio;
    }

    /**
     * @return array
     */
    public static function getConfigCropGallery()
    {
        $module = \Yii::$app->getModule('attachments');
        $disableFreeCropGallery = $module->disableFreeCropGallery;
        $aspectRatioChoices = [
            [
                'title' => FileModule::t('amosattchments', 'Elimina Crop'),
                'value' => 'x',
                'label' => AmosIcons::show('close'),
            ],
            [
                'title' => '16:9',
                'value' => '1.7',
                'label' => '16:9',
            ],
            [
                'title' => '1:1',
                'value' => '1',
                'label' => '1:1',
            ],
        ];

        if (!$disableFreeCropGallery) {
            $aspectRatioChoices = ArrayHelper::merge($aspectRatioChoices, [
                [
                    'title' => FileModule::t('amosattchments', 'Fattore di crop libero Crop'),
                    'value' => 'NaN',
                    'label' => FileModule::t('amosattchments', 'Libero'),
                ]
            ]);
        }

        return $aspectRatioChoices;
    }
    
    /**
     * 
     * @param type $tagsList
     * @return type
     */
    public static function formatTags($tagsList)
    {
        $tmp = '';
        foreach ($tagsList as $tag) {
            $tmp .= Html::tag(
                'span',
                $tag->nome,
                [
                    'class' => 'label label-default'
                ]
            );
        }
        
        return $tmp;
    }

}
