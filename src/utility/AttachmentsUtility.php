<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 03/09/2021
 * Time: 14:49
 */

namespace open20\amos\attachments\utility;


use open20\amos\attachments\FileModule;
use yii\helpers\ArrayHelper;

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
                'label' => \open20\amos\core\icons\AmosIcons::show('close'),
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


}