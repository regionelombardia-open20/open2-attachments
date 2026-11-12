<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   amos\sitemanagement\widgets\icons
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

namespace open20\amos\attachments\widgets\icons;

use open20\amos\attachments\FileModule;
use open20\amos\core\widget\WidgetIcon;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconSiteManagementSlider
 * @package amos\sitemanagement\widgets\icons
 */
class WidgetIconGalleryDashboard extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $module = \Yii::$app->getModule('attachments');
        $this->setLabel(FileModule::tHtml('amosattachments', '#label_gallery_image_databank'));
        $this->setDescription(FileModule::t('amosattachments', '#desc_gallery_image'));
        $this->setIcon('collection-image');
        $this->setIconFramework('am');

        if ($module->enableSingleGallery) {
            $this->setUrl(['/attachments/attach-gallery/single-gallery']);
        } else {
            $this->setUrl(['/attachments/attach-gallery']);
        }

        $this->setCode('ATTACHMENTS_GALLERY');
        $this->setModuleName('attachments');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                [
                    'bk-backgroundIcon',
                    'color-lightPrimary'
                ]
            )
        );
    }

}
