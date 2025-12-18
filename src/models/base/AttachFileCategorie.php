<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    package
 */

namespace open20\amos\attachments\models\base;

use open20\amos\attachments\FileModule;
use open20\amos\attachments\models\AttachFileCategorieAssetsMm;
use Yii;

/**
 * This is the base-model class for table "attach_file_categorie".
 *
 * @property integer $id
 * @property string $titolo
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property AttachFileCategorieAssetsMm[] $assets
 */
class AttachFileCategorie extends \open20\amos\core\record\Record
{
    const MAX_LENGTH_TITLE = 50;

    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attach_file_categorie';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['titolo'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
//            [['titolo'], 'string', 'max' => 255],
            [['titolo'], 'string', 'max' => self::MAX_LENGTH_TITLE],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FileModule::t('amosattachments', 'ID'),
            'titolo' => FileModule::t('amosattachments', 'Titolo'),
            'created_at' => FileModule::t('amosattachments', 'Created At'),
            'updated_at' => FileModule::t('amosattachments', 'Updated At'),
            'deleted_at' => FileModule::t('amosattachments', 'Deleted At'),
            'created_by' => FileModule::t('amosattachments', 'Created By'),
            'updated_by' => FileModule::t('amosattachments', 'Updated By'),
            'deleted_by' => FileModule::t('amosattachments', 'Deleted By'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssets()
    {
        return $this->hasMany(AttachFileCategorieAssetsMm::class,
            ['attach_file_categorie_id' => 'id'])->inverseOf('categoria');
    }
}