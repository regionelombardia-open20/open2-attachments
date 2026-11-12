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