<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m230901_161503_create_attach_file_categorie
 */
class m230901_161503_create_attach_file_categorie extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%attach_file_categorie}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'titolo' => $this->string(255)->notNull(),
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
    }
}
