<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core
 * @category   CategoryName
 */

namespace open20\amos\attachments\utilities;

use open20\amos\attachments\FileModule;
use open20\amos\attachments\models\FileRefs;
use open20\amos\attachments\models\File;

use Yii;
use yii\log\Logger;

class AwsUtility
{
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
                    $origin = $fileRef->getPath($fileRef->crop);
                    if (!empty($origin)) {
                        $path = self::getS3PathByLocalPath($origin);
                        $url  = self::execUploadToS3($origin, $path);
                        if (!empty($url)) {
                            $fileRef->s3_url = $url;
                            $fileRef->save(false);
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
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
        } catch (Exception $ex) {
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
            if (strpos($res, 'Completed') !== false) {
                $url = $aws_s3_base_url.'/'.$s3_url;
            }
        } catch (Exception $ex) {
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
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
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
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
    }
}