<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments
 * @category   CategoryName
 */

namespace open20\amos\attachments;

/**
 * Class FileModuleTrait
 * @package open20\amos\attachments
 */
trait FileModuleTrait
{
    /**
     * @var null|\open20\amos\attachments\FileModule
     */
    private $_module = null;

    /**
     * @return null|\open20\amos\attachments\FileModule
     * @throws \Exception
     */
    protected function getModule()
    {
        if ($this->_module == null) {
            $this->_module = \Yii::$app->getModule(FileModule::getModuleName());
        }

        if (!$this->_module) {
            throw new \Exception(FileModule::t('amosattachments', "amos-attachments module not found, may be you didn't add it to your config?"));
        }

        return $this->_module;
    }
}
