<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-attachments
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

namespace open20\amos\attachments\validators;

use open20\amos\attachments\FileModule;

class PdfContainsJSValidator extends \yii\validators\Validator {
    /**
     * @param string $file
     * @param string $error
     * @return bool
     */
    public function validate($path, &$error = null) {
        // Parse PDF file and build necessary objects.
        $parser = new \Smalot\PdfParser\Parser();

        // Analizza il PDF
        $pdf = $parser->parseFile($path);

        $action = $pdf->getDictionary()['Action'];
        $catalog = $pdf->getDictionary()['Catalog'];
        $page = $pdf->getDictionary()['Page'];
        $pages = $pdf->getDictionary()['Pages'];

        $js_found = false;

        // Action
        foreach($action as $act) {
            foreach($act as $actType) {
                $detailAction = $actType->getDetails();
                $js_found |= self::findJsKey($detailAction);
            }
        }

        // Catalog
        foreach($catalog as $cat) {
            foreach($cat as $catType) {
                $detailCat = $catType->getDetails();
                $js_found |= self::findJsKey($detailCat);
            }
        }

        // Page
        foreach($page as $p) {
            foreach($p as $pType) {
                $detailPage = $pType->getDetails();
                $js_found |= self::findJsKey($detailPage);
            }
        }

        // Pages
        foreach($pages as $p) {
            foreach($p as $pType) {
                $detailPages = $pType->getDetails();
                $js_found |= self::findJsKey($detailPages);
            }
        }

        if (!$js_found) {
            return true;
        } else {
            return false;
        }
    }

    /** Per utilizzarlo con i model
     * @param $model
     * @param $attribute
     * @return bool
     */
    public function validateAttribute($model, $attribute) {
        // Parse PDF file and build necessary objects.
        $parser = new \Smalot\PdfParser\Parser();

        // Analizza il PDF
        $pdf = $parser->parseFile($model->getPath());

        $action = $pdf->getDictionary()['Action'];
        $catalog = $pdf->getDictionary()['Catalog'];
        $page = $pdf->getDictionary()['Page'];
        $pages = $pdf->getDictionary()['Pages'];

        $js_found = false;

        // Action
        foreach($action as $act) {
            foreach($act as $actType) {
                $detailAction = $actType->getDetails();
                $js_found |= self::findJsKey($detailAction);
            }
        }

        // Catalog
        foreach($catalog as $cat) {
            foreach($cat as $catType) {
                $detailCat = $catType->getDetails();
                $js_found |= self::findJsKey($detailCat);
            }
        }

        // Page
        foreach($page as $p) {
            foreach($p as $pType) {
                $detailPage = $pType->getDetails();
                $js_found |= self::findJsKey($detailPage);
            }
        }

        // Pages
        foreach($pages as $p) {
            foreach($p as $pType) {
                $detailPages = $pType->getDetails();
                $js_found |= self::findJsKey($detailPages);
            }
        }

        if (!$js_found) {
            return true;
        } else {
            $this->addError($model, $attribute, FileModule::t('amosattachments', '#pdf_with_js_warning'));
            return false;
        }
    }

    /** Funzione ricorsiva per analizzare i contenuti del PDF
     * @param array $array
     * @return bool
     */
    public static function findJsKey($array) {
        if(!is_array($array)) {
            return false;
        }
        $keys = array_keys($array);
        $found = !empty(array_intersect(['JavaScript', 'JS'], $keys));
        $i = 0;
        while(!$found && $i < count($keys)) {
            $found = self::findJsKey($array[$keys[$i]]);
            $i++;
        }
        return $found;
    }
}