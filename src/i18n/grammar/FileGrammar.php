<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\i18n\grammar
 * @category   CategoryName
 */

namespace open20\amos\attachments\i18n\grammar;

use open20\amos\attachments\FileModule;
use open20\amos\core\interfaces\ModelGrammarInterface;

/**
 * Class UserProfileGrammar
 * @package open20\amos\admin\i18n\grammar
 */
class FileGrammar implements ModelGrammarInterface
{
    /**
     * @return string The singular model name in translation label
     */
    public function getModelSingularLabel()
    {
        return FileModule::t('amosattachments', '#file_singular');
    }
    
    /**
     * @return string The model name in translation label
     */
    public function getModelLabel()
    {
        return FileModule::t('amosattachments', '#file_plural');
    }
    
    /**
     * @return string
     */
    public function getArticleSingular()
    {
        return FileModule::t('amosattachments', '#article_singular');
    }
    
    /**
     * @return string
     */
    public function getArticlePlural()
    {
        return FileModule::t('amosattachments', '#article_plural');
    }
    
    /**
     * @return string
     */
    public function getIndefiniteArticle()
    {
        return FileModule::t('amosattachments', '#article_indefinite');
    }
}
