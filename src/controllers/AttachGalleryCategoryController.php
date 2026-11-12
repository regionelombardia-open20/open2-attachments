<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\attachments\controllers
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
