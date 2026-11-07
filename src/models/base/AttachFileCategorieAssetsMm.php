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
use open20\amos\attachments\models\AttachFileCategorie;
use Yii;

/**
 * This is the base-model class for table "attach_file_categorie_assets_mm".
 *
 * @property integer $id
 * @property integer $attach_file_categorie_id
 * @property string $classe_asset
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property AttachFileCategorie $categoria
 */
class AttachFileCategorieAssetsMm extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attach_file_categorie_assets_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attach_file_categorie_id', 'classe_asset'], 'required'],
            [['attach_file_categorie_id', 'created_by', 'updated_by', 'deleted_by'],
                'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['classe_asset'], 'string', 'max' => 255],
            [['attach_file_categorie_id'], 'exist', 'skipOnError' => true,
                'targetClass' => AttachFileCategorie::className(),
                'targetAttribute' => ['attach_file_categorie_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FileModule::t('amosattachments', 'ID'),
            'attach_file_categorie_id' => FileModule::t('amosattachments',
                'Categoria ID'),
            'classe_asset' => FileModule::t('amosattachments', 'Classe Asset'),
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
    public function getCategoria()
    {
        return $this->hasOne(AttachFileCategorie::class,
            ['id' => 'attach_file_categorie_id']);
    }
}