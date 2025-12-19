<?php

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