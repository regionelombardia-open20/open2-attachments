<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m230901_181732_create_attach_file_categorie_assets_mm
 */
class m230901_181732_create_attach_file_categorie_assets_mm extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%attach_file_categorie_assets_mm}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'attach_file_categorie_id' => $this->integer()->notNull(),
            'classe_asset' => $this->string(255)->notNull(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeTableCreation()
    {
        parent::beforeTableCreation();
        $this->setAddCreatedUpdatedFields(true);
    }

    /**
     * @inheritdoc
     */
    protected function afterTableCreation()
    {
    }

    /**
     * @inheritdoc
     */
    protected function addForeignKeys()
    {
        $this->addForeignKey(
            $this->getRawTableName() . '_attach_file_categorie_id',
            $this->getRawTableName(),
            'attach_file_categorie_id',
            \open20\amos\attachments\models\AttachFileCategorie::tableName(),
            'id',
            'RESTRICT',
            'RESTRICT');
    }
}
