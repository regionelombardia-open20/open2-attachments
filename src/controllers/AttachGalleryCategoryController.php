<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\controllers 
 */
 
namespace open20\amos\attachments\controllers;

use open20\amos\attachments\FileModule;
use yii\helpers\Html;

/**
 * Class AttachGalleryCategoryController 
 * This is the class for controller "AttachGalleryCategoryController".
 * @package open20\amos\attachments\controllers 
 */
class AttachGalleryCategoryController extends \open20\amos\attachments\controllers\base\AttachGalleryCategoryController
{
    /**
     *
     * @return array
     */
    public static function getManageLinks()
    {
        $links = [];
        if (\Yii::$app->user->can(\open20\amos\attachments\widgets\icons\WidgetIconCategory::class)) {
            $links[] = [
                'title' => FileModule::t('amosattachments', 'Visualizza e gestisci la gallery'),
                'label' => FileModule::t('amosattachments', 'Gallery'),
                'url' => '/attachments/attach-gallery/single-gallery'
            ];
        }


        return $links;
    }

     public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            $titleSection = FileModule::t('amosattachments', 'Categorie');
            $urlLinkAll   = '';

            $ctaLoginRegister = Html::a(
                FileModule::t('amosattachments', 'accedi o registrati alla piattaforma'),
                isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon']) ? \Yii::$app->params['linkConfigurations']['loginLinkCommon']
                    : \Yii::$app->params['platform']['backendUrl'] . '/' . AmosAdmin::getModuleName() . '/security/login',
                [
                    'title' => FileModule::t('amosattachments',
                        'Clicca per accedere o registrarti alla piattaforma {platformName}',
                        ['platformName' => \Yii::$app->name]
                    )
                ]
            );
            $subTitleSection  = Html::tag(
                'p',
                FileModule::t('amosattachments',
                    'Per partecipare {ctaLoginRegister}',
                    ['ctaLoginRegister' => $ctaLoginRegister]
                )
            );
        } else {
            $titleSection = FileModule::t('amosattachments', 'Categorie');
                       $subTitleSection = Html::tag('p', FileModule::t('amosattachments', ''));
        }

        $labelCreate = FileModule::t('amosattachments', 'Nuova');
        $titleCreate = FileModule::t('amosattachments', 'Aggiungi una nuova categoria');
        $labelManage = FileModule::t('amosattachments', 'Gestisci');
        $titleManage = FileModule::t('amosattachments', 'Gestisci le immagini');
        $urlCreate   = '/attachments/attach-gallery-category/create';
        $urlManage   = '#';

        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'attachments',
            'titleSection' => $titleSection,
            'subTitleSection' => $subTitleSection,
            'labelCreate' => $labelCreate,
            'titleCreate' => $titleCreate,
            'labelManage' => $labelManage,
            'titleManage' => $titleManage,
            'urlCreate' => $urlCreate,
            'urlManage' => $urlManage,
        ];

        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here

        return true;
    }

}
