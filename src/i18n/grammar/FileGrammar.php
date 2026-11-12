<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\admin\i18n\grammar
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
