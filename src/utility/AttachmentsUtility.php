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
use open20\amos\attachments\models\File;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;

use open20\amos\utility\models\PlatformConfigs;
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
                'label' => 'Nessun crop',
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

    /**
     * @param  $model
     * @param bool $onlyIconName If true return only the icon name ready to use in AmosIcon::show method.
     * @return string
     */
    public static function getAttachmentIcon($model, $onlyIconName = false)
    {
        $iconName = 'file-o';
        $classDocIcon = 'icon icon-link icon-sm mdi mdi-link-box';


        $documentFile = $model;
        if (!is_null($documentFile)) {
            $docExtension = strtolower($documentFile->type);
            $extensions = [
                'doc' => 'file-doc-o',
                'docx' => 'file-docx-o',
                'rtf' => 'file-rtf-o',
                'xls' => 'file-xls-o',
                'xlsx' => 'file-xlsx-o',
                'txt' => 'file-txt-o',
                'pdf' => 'file-pdf-o2',
            ];

            $extensionsBefe = [
                'jpg' => 'icon icon-image icon-sm mdi mdi-file-jpg-box',
                'png' => 'icon icon-image icon-sm mdi mdi-file-png-box',
                'jpeg' => 'icon icon-image icon-sm mdi mdi-file-jpg-box',
                'gif' => 'icon icon-image icon-sm mdi mdi-file-gif-box',
                'svg' => 'icon icon-image icon-sm mdi mdi-svg',
                'pdf' => 'icon icon-pdf icon-sm mdi mdi-file-pdf-box',
                'docx' => 'icon icon-word icon-sm mdi mdi-file-word-box',
                'doc' => 'icon icon-word icon-sm mdi mdi-file-word-box',
                'ppt' => 'icon icon-powerpoint icon-sm mdi mdi-file-powerpoint-box',
                'pptx' => 'icon icon-powerpoint icon-sm mdi mdi-file-powerpoint-box',
                'xls' => 'icon icon-excel icon-sm mdi mdi-file-excel-box',
                'xlsx' => 'icon icon-excel icon-sm mdi mdi-file-excel-box',
                'csv' => 'icon icon-black icon-sm mdi mdi-text-box',
                'rtf' => 'icon icon-black icon-sm mdi mdi-text-box',
                'txt' => 'icon icon-black icon-sm mdi mdi-text-box',
                'zip' => 'icon icon-link icon-sm mdi mdi-zip-box',
                'rar' => 'icon icon-link icon-sm mdi mdi-zip-box',
            ];

            if (!empty(\Yii::$app->params['befe'])) {
                if (!empty($extensionsBefe[$docExtension])) {
                    $classDocIcon = $extensionsBefe[$docExtension];
                } else {
                    $classDocIcon = 'icon icon-link icon-sm mdi mdi-link-box';
                }
            }

            if (isset($extensions[$docExtension])) {
                $iconName = $extensions[$docExtension];
            }
        }

        if ($onlyIconName) {
            return $iconName;
        }


        if (!empty(\Yii::$app->params['befe'])) {
            return Html::tag('span', null, ['class' => $classDocIcon, 'title' => $docExtension]);
        }
        return AmosIcons::show($iconName, ['class' => 'icon_widget_graph'], 'dash');

    }


    /**
     * @param $dataProvider
     * @return void
     */
    public static function filterFilesDoesntExit($dataProvider)
    {
        $models = $dataProvider->models;
        foreach ($models as $k => $item) {
            $filePath = $item->getPath();
            if (!file_exists($filePath)) {
                unset($models[$k]);
            }
        }
        $dataProvider->setModels($models);

    }

    /**
     * @param $size
     * @return string
     */
    public static function getFormattedSize($size)
    {
        {
            $dimScale = ['B', 'Kb', 'Mb', 'Gb', 'Tb'];
            $sizeFile = intval($size);
            $power = $sizeFile > 0
                ? floor(log($sizeFile, 1024))
                : 0;

            return number_format(
                    $sizeFile / pow(1024, $power),
                    0,
                    '.',
                    ','
                )
                . ' '
                . $dimScale[$power];
        }
    }

    /**
     * @param $attach_file_id
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public static function deleteCmsFile($attach_file_id)
    {
        $file = File::findOne($attach_file_id);
        if ($file) {
            $file->original_attach_file_id = $attach_file_id;
            $file->save(false);
            $storage = $file->getAdminStorageFile();
            if ($storage) {
                $storage->deleteCms();
                return true;
            }
        }
        return false;
    }

    /**
     * @param $key
     * @return mixed|string|null
     */
    public static function getPlatformConfig($key)
    {
        $config = PlatformConfigs::find()->andWhere(['module' => 'attachments', 'key' => $key])->one();
        if ($config) {
            return $config->value;
        }
        return '';
    }

    /**
     * @param $key
     * @param $value
     * @return array|PlatformConfigs|\yii\db\ActiveRecord|null
     */
    public static function savePlatformConfig($key, $value)
    {

        $config = PlatformConfigs::find()->andWhere(['module' => 'attachments', 'key' => $key])->one();
        if (empty($config)) {
            $config = new PlatformConfigs();
            $config->module = 'attachments';
            $config->key = $key;
        }
        $config->value = $value;
        $config->save(false);

        return $config;
    }


}
