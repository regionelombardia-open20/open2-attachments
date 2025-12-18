<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * 
 */
class m230930_110813_create_check_table extends Migration {

    const TABLE = "attach_file_not_exists";
    const TABLE_REF = "attach_file";

    /**
     * @inheritdoc
     */
    public function up() {

        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            $this->createTable(self::TABLE, [
                'attach_file_id' => $this->integer()->notNull(),
                'check_ok' => $this->integer()->defaultValue(1),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' => $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' => $this->integer()->comment('Created by'),
                'updated_by' => $this->integer()->comment('Updated at'),
                'deleted_by' => $this->integer()->comment('Deleted at'),
                    ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addPrimaryKey('pk_attach_file_id', self::TABLE, 'attach_file_id');
            $this->addForeignKey('fk_attach_file_id', self::TABLE, 'attach_file_id', self::TABLE_REF, 'id');
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }
    }

    /**
     * @inheritdoc
     */
    public function down() {

        $this->dropTable(self::TABLE);
    }
}
