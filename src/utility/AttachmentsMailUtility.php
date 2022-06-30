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
use open20\amos\attachments\models\AttachGalleryRequest;
use open20\amos\core\user\User;
use open20\amos\core\utilities\Email;

use yii\log\Logger;

/**
 * 
 */
class AttachmentsMailUtility
{
    /**
     * @param $to
     * @param $profile
     * @param $subject
     * @param $message
     * @param array $files
     * @return bool
     */
    public static function sendEmailGeneral($to, $subject, $message, $files = [])
    {
        try {
            $from = '';
            if (isset(\Yii::$app->params['email-assistenza'])) {
                //use default platform email assistance
                $from = \Yii::$app->params['email-assistenza'];
            }

            /** @var \open20\amos\core\utilities\Email $email */
            $email = new Email();
            $email->sendMail($from, $to, $subject, $message, $files);
        } catch (\Exception $ex) {
            //pr($ex->getMessage());
            \Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return true;
    }

    /**
     * @param $model AttachGalleryRequest
     * @throws \yii\base\InvalidConfigException
     */
    public static function sendEmailRequestImage($model)
    {
        /**@var $model AttachGalleryRequest */
        $subject = FileModule::t('amosattachments', 'Databank immagini - Richiesta immagine');
        $link = \Yii::$app->params['platform']['backendUrl']
            . '/attachments/attach-gallery-request/update?id='
            . $model->id;

        $userDefault = null;
        $userIds = \Yii::$app->authManager->getUserIdsByRole('ATTACH_IMAGE_REQUEST_OPERATOR');
        foreach ($userIds as $user_id) {
            $user = User::findOne($user_id);

            $message = FileModule::t('amosattachments', "Gentile <strong>{nome} {cognome}</strong>,
<br>è stata richiesta un'immagine con le seguenti caratteristiche:", [
                'nome' => $user->userProfile->nome,
                'cognome' => $user->userProfile->cognome,
            ]);
            $message .= "<br><strong>" . FileModule::t('amosattachments', "ID Richiesta") . '</strong>: ' . $model->id;
            $message .= "<br><strong>" . FileModule::t('amosattachments', "Titolo immagine") . '</strong>: ' . $model->title;
            $message .= "<br><strong>" . FileModule::t('amosattachments', "Tag di interesse informativo") . '</strong>: ' . $model->getTagImagesString();
            $message .= "<br><strong>" . FileModule::t('amosattachments', "Tag liberi") . '</strong>: ' . $model->getCustomTagsString();
            $message .= "<br><strong>" . FileModule::t('amosattachments', "Aspect ratio") . '</strong>: ' . $model->aspect_ratio;
            $message .= "<br><strong>" . FileModule::t('amosattachments', "Text request") . '</strong>: ';
            $message .= "<br>" . $model->text_request;
            $message .= "<br><br>" . FileModule::t('amosattachments', "Clicca <a href='{link}'>qui</a> per visualizzare la Richiesta in piattaforma", [
                    'link' => $link
                ]);

            self::sendEmailGeneral([$user->email], $subject, $message);
        }

    }

    /**
     * @param $model AttachGalleryRequest
     * @throws \yii\base\InvalidConfigException
     */
    public static function sendEmailRequestImageUploaded($model)
    {
        /**@var $model AttachGalleryRequest */
        $subject = FileModule::t('amosattachments', 'Databank Immagini – Caricamento effettuato per la richiesta {idrichiesta}', [
            'idrichiesta' => $model->id
        ]);
        $link = \Yii::$app->params['platform']['backendUrl'] . '/attachments/attach-gallery-request/view?id=' . $model->id;

        $userDefault = null;
        $user = User::find()->andWhere(['id' => $model->created_by])->one();

        $message = FileModule::t('amosattachments', "Gentile <strong>{nome} {cognome}</strong>,
<br>è stata caricata l’immagine come da vostra richiesta.", [
            'nome' => $user->userProfile->nome,
            'cognome' => $user->userProfile->cognome,
        ]);
        $message .= "<br>" . FileModule::t('amosattachments', "ID Richiesta") . ': ' . $model->id;
        $message .= "<br>" . FileModule::t('amosattachments', "Titolo immagine") . ': ' . $model->title;
        $message .= "<br><br>" . FileModule::t('amosattachments', "Clicca <a href='{link}'>qui</a> per visualizzare l'immagine", [
                'link' => $link
            ]);

        self::sendEmailGeneral([$user->email], $subject, $message);
    }

}