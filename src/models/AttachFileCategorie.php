<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    package
 */

namespace open20\amos\attachments\models;

use open20\amos\attachments\FileModule;
use open20\amos\attachments\models\AttachFileCategorieAssetsMm;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "attach_file_categorie".
 */
class AttachFileCategorie extends \open20\amos\attachments\models\base\AttachFileCategorie
{
    public $assetClassesForm;

    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
//inserire il campo o i campi rappresentativi del modulo
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['assetClassesForm'], 'each', 'rule' => ['string']],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(
            parent::attributeLabels(), [
                'assets' => FileModule::t('amosattachments', 'Asset Abilitati'),
                'assetClassesForm' => FileModule::t('amosattachments',
                    'Asset')
            ]);
    }

    public function getEditFields()
    {
        $labels = $this->attributeLabels();

        return [
            [
                'slug' => 'titolo',
                'label' => $labels['titolo'],
                'type' => 'string'
            ],
        ];
    }

    /**
     * @return string marker path
     */
    public function getIconMarker()
    {
        return null; //TODO
    }

    /**
     * If events are more than one, set 'array' => true in the calendarView in the index.
     * @return array events
     */
    public function getEvents()
    {
        return NULL; //TODO
    }

    /**
     * @return url event (calendar of activities)
     */
    public function getUrlEvent()
    {
        return NULL; //TODO e.g. Yii::$app->urlManager->createUrl([]);
    }

    /**
     * @return color event
     */
    public function getColorEvent()
    {
        return NULL; //TODO
    }

    /**
     * @return title event
     */
    public function getTitleEvent()
    {
        return NULL; //TODO
    }

    /**
     * Retrieves the names of assets enabled for the category
     * @return string[] Returns the names of the assets enabled for the category
     */
    public function getAssetNames() {
        $res = [];
        foreach ($this->assets as $asset) {
            $nome = self::getAssetName($asset->classe_asset);
            if (!empty($nome)) {
                $res[] = $nome;
            }
        }
        return $res;
    }

    /**
     * Retrieve the name of the asset whose class is passed to the method
     * @param string $assetClass Class of the asset whose name to retrieve
     * @return string Returns the asset name or empty string if the asset is not
     * present or the name could not be retrieved
     */
    public static function getAssetName($assetClass) {
        if ((empty($assetClass)) || (!class_exists($assetClass))) {
            return '';
        }
        $model = new $assetClass;
        if ($model->hasMethod('getGrammar')) {
            $grammar = $model->getGrammar();
            if ((!empty($grammar)) && (method_exists($grammar,
                'getModelLabel'))) {
                $nome = $grammar->getModelLabel();
                if (!empty($nome)) {
                    return ucfirst($nome);
                }
            }
        }
        return ucfirst(end(explode('\\', $assetClass)));
    }

    /**
     * Generates the data necessary to populate the menu relating to the
     * possible assets that can be associated with the category
     * @return array Returns an array that has asset classes as indexes and
     * their names as values
     */
    public static function getDateForAssetMenu() {
        $modulo = FileModule::instance();
        if ((empty($modulo->categorizationEnabled))
            || (empty($modulo->categorizationModelsEnabled))) {
            throw new InvalidConfigException(FileModule::t('amosattachments',
                'La categorizzazione non è abilitata o configurata'));
        }
        $res = [];
        foreach ($modulo->categorizationModelsEnabled as $asset) {
            $nome = self::getAssetName($asset);
            if (!empty($nome)) {
                $res[$asset] = $nome;
            }
        }
        return $res;
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();

        //recupero gli enti che possono visualizzare l'avviso
        $this->assetClassesForm = $this->getAssets()
            ->select(AttachFileCategorieAssetsMm::tableName() . '.classe_asset')
            ->column();
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        //aggiorni gli enti che possono visualizzare l'avviso
        if (!$this->updateAssetCaterories()) {
            //al momento lo ignoro
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Update connection with asset caterories
     * @return boolean Returns true on success or false otherwise
     */
    protected function updateAssetCaterories()
    {
        $oldMms = $this->assets;
        
        /**
         * se non viene impostato alcun asset verranno presi tutti quelli
         * possibili
         */
        if (empty($this->assetClassesForm)) {
            $this->assetClassesForm = array_keys(self::getDateForAssetMenu());
        }
        
        if ((empty($oldMms)) && (empty($this->assetClassesForm))) {
            //non ci sono classi collegati e non ce n'erano in passato
            return true;
        }
        if ((!empty($oldMms)) && (empty($this->assetClassesForm))) {
            //cancello tutti i model mm
            /** @var AttachFileCategorieAssetsMm $modelMm */
            foreach ($oldMms as $modelMm) {
                $modelMm->delete();
            }
            return true;
        }
        if ((empty($oldMms)) && (!empty($this->assetClassesForm))) {
            //tutti i collegamenti con gli enti sono da memorizzare
            foreach ($this->assetClassesForm as $classeAsset) {
                $modelMm = new AttachFileCategorieAssetsMm();
                $modelMm->attach_file_categorie_id = $this->id;
                $modelMm->classe_asset = $classeAsset;
                try {
                    if (!$modelMm->save()) {
//                    	pr($modelMm->getErrors());
                        return false;
                    }
                } catch (\Exception $exc) {
//                	echo $exc->getMessage();
                    return false;
                }
            }
            return true;
        }
        $idsAssetsOld = ArrayHelper::map($oldMms, 'id', 'classe_asset');
        $idsAssetsNew = array_diff($this->assetClassesForm, $idsAssetsOld);
        $idsAssetsToDel = array_diff($idsAssetsOld, $this->assetClassesForm);
        if (!empty($idsAssetsNew)) {
            //inserisco i nuovi collegamenti con gli enti
            foreach ($idsAssetsNew as $classeAsset) {
                $modelMm = new AttachFileCategorieAssetsMm();
                $modelMm->attach_file_categorie_id = $this->id;
                $modelMm->classe_asset = $classeAsset;
                try {
                    if (!$modelMm->save()) {
//                    	pr($modelMm->getErrors());
                        return false;
                    }
                } catch (\Exception $exc) {
//                	echo $exc->getMessage();
                    return false;
                }
            }
        }
        if (!empty($idsAssetsToDel)) {
            //cancello i vecchi collegamenti non piu' presenti
            /** @var AttachFileCategorieAssetsMm $modelMm */
            foreach ($oldMms as $modelMm) {
                if (!in_array($modelMm->classe_asset, $idsAssetsToDel)) {
                    continue;
                }
                $modelMm->delete();
            }
        }
        return true;
    }

    /**
     * Checks whether the category is valid for the model class passed to the
     * method
     * @param string $modelClass Class of the model to check
     * @return bool Returns true if the category is valid for the model or false
     * otherwise
     */
    public function isValidForModel($modelClass)
    {
        if (empty($modelClass)) {
            return false;
        }
        return (!empty($this->getAssets()
            ->andWhere(['classe_asset' => $modelClass])->count()));
    }
}