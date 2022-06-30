<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 03/09/2021
 * Time: 14:49
 */

namespace open20\amos\attachments\utility;


class AttachmentsUtility
{
    /**
     * @param $ratio
     * @return string
     */
    public static function getFormattedAspectRatio($ratio){
        $formattedRatio = 'other';
        switch($ratio){
            case '1':
                $formattedRatio = '1:1';
                break;
            case '1.7':
                $formattedRatio = '16:9';
                break;
        }
        return $formattedRatio;
    }


}