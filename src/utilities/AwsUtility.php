<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\core
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

namespace open20\amos\attachments\utilities;

use open20\amos\attachments\FileModule;
use open20\amos\attachments\models\FileRefs;
use open20\amos\attachments\models\File;

use Yii;
use yii\log\Logger;

class AwsUtility
{
    public static $messages;

    /**
     *
     * @param string $hash
     * @param bool $force_upload
     */
    public static function uploadS3ByHash($hash, $force_upload = false)
    {
        try {
            $module = FileModule::getInstance();
            if ($module->enable_aws_s3) {
                $fileRef = FileRefs::findOne(['hash' => $hash]);
                if (
                    !empty($fileRef)
                    && !$fileRef->protected
                    && (
                        $force_upload
                        || empty($fileRef->s3_url)
                    )
                ) {
                    $origin = $fileRef->getPath();
                    if (!empty($origin)) {
                        $path = self::getS3PathByLocalPath($origin);
                        $url  = self::execUploadToS3($origin, $path);

//                        echo $url . " - ".$path. "\n <br>";
                        self::$messages []=
                            '<strong>Url: </strong>'.$url . " <br>"
                            .'<strong>Path: </strong>'.$path. "<br>"
                            .'<strong>Crop: </strong>'.$fileRef->crop. "<br>";

                        if (!empty($url)) {
                            $fileRef->s3_url = $url;
                            $fileRef->save(false);
                            return true;
                        }
                    }
                }
            }
            return false;
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
            return false;
        }
    }

    /**
     *
     * @param File $file
     * @param string $local_path
     * @param string $crop
     * @param FileRefs $fileRef
     * @param bool $force_upload
     */
    public static function uploadS3ByPath(
        File $file,
        $local_path,
        $crop,
        FileRefs $fileRef = null,
        $force_upload = false
    )
    {
        try {
            $module = FileModule::getInstance();
            if ($module->enable_aws_s3) {
                $path = self::getS3PathByLocalPath($local_path);
                $url  = self::execUploadToS3($local_path, $path);
                if (!empty($url)) {
                    if (empty($fileRef)) {
                        $fileRef = FileRefs::find()->andWhere([
                            'attach_file_id' => $file->id,
                            'model' => $file->model,
                            'item_id' => $file->item_id,
                            'attribute' => $file->attribute,
                            'crop' => $crop,
                        ])
                        ->one();
                    }
                    
                    if (
                        !empty($fileRef)
                        && !$fileRef->protected
                        && (
                            $force_upload
                            || empty($fileRef->s3_url)
                        )
                    ) {
                        $fileRef->s3_url = $url;
                        $fileRef->save(false);
                    }
                }
            }
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
    }

    /**
     *
     * @param string $local_path
     * @param string $s3_path
     * @return string
     */
    protected static function execUploadToS3($local_path, $s3_path)
    {
        $url = null;
        try {
            $module                = FileModule::getInstance();
            $aws_s3_access_key     = $module->aws_s3_access_key;
            $aws_s3_secret_key     = $module->aws_s3_secret_key;
            $aws_s3_base_dir       = $module->aws_s3_base_dir;
            $aws_s3_base_url       = $module->aws_s3_base_url;
            $aws_s3_default_region = $module->aws_s3_default_region;
            $bucket                = $module->aws_s3_bucket;
            $s3_url                = $aws_s3_base_dir.'/'.$s3_path;
            putenv("AWS_ACCESS_KEY_ID=$aws_s3_access_key");
            putenv("AWS_SECRET_ACCESS_KEY=$aws_s3_secret_key");
            putenv("AWS_DEFAULT_REGION=$aws_s3_default_region");
            $res                   = exec("aws s3 cp $local_path 's3://$bucket/$s3_url' --cache-control max-age={$module->cache_age}");
//            pr("aws s3 cp $local_path 's3://$bucket/$s3_url' --cache-control max-age={$module->cache_age}");
            if (strpos($res, 'Completed') !== false) {
                $url = $aws_s3_base_url.'/'.$s3_url;
            }
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }

        return $url;
    }

    /**
     *
     * @param string $local_path
     * @return string
     */
    protected static function getS3PathByLocalPath($local_path)
    {
        try {
            $pos  = strpos($local_path, 'store');
            $path = substr($local_path, $pos + 6);
            return $path;
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
            return null;
        }
    }

    /**
     *
     * @param string $path
     */
    public static function deleteFileFromS3($path)
    {
        try {
            $module = FileModule::getInstance();
            if ($module->enable_aws_s3) {
                $aws_s3_access_key     = $module->aws_s3_access_key;
                $aws_s3_secret_key     = $module->aws_s3_secret_key;
                $aws_s3_base_url       = $module->aws_s3_base_url;
                $awsPath               = str_replace($aws_s3_base_url, '', $path);
                $aws_s3_default_region = $module->aws_s3_default_region;
                $bucket                = $module->aws_s3_bucket;
                putenv("AWS_ACCESS_KEY_ID=$aws_s3_access_key");
                putenv("AWS_SECRET_ACCESS_KEY=$aws_s3_secret_key");
                putenv("AWS_DEFAULT_REGION=$aws_s3_default_region");
                exec("aws s3 rm 's3://{$bucket}{$awsPath}'");
            }
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
    }
}