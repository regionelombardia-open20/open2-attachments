<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\widgets\icons
 * @category   CategoryName
 */

namespace open20\amos\attachments\widgets\icons;

use open20\amos\attachments\FileModule;
use open20\amos\core\widget\WidgetIcon;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconSiteManagementSlider
 * @package amos\sitemanagement\widgets\icons
 */
class WidgetIconGalleryImageRequestOpened extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(FileModule::tHtml('amosattachments', 'Richieste aperte'));
        $this->setDescription(FileModule::t('amosattachments', 'Richieste aperte'));
        $this->setIcon('linentita');
        $this->setUrl(['/attachments/attach-gallery-request/opened']);
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
