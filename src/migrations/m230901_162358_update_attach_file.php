<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 */

use yii\db\Migration;

/**
 * Class m230901_162358_update_attach_file
 */
class m230901_162358_update_attach_file extends Migration
{
    const NOME_TABELLA = 'attach_file';
    const NOME_COLONNA = 'attach_file_categorie_id';

    /**
     * @inheritdoc
     */
    public function safeUp() {
	if (is_null($this->db->schema->getTableSchema(
            self::NOME_TABELLA, true))) {
            echo "Non trovo la tabella '" . self::NOME_TABELLA . "'";
            return false;
	}
        $this->addColumn(self::NOME_TABELLA, self::NOME_COLONNA,
            $this->integer()->defaultValue(null)->null()
            ->after('table_name_form'));
        
        $this->execute("SET FOREIGN_KEY_CHECKS = 0");
        $this->addForeignKey(self::NOME_TABELLA . '_' . self::NOME_COLONNA,
            self::NOME_TABELLA,
            self::NOME_COLONNA,
            'attach_file_categorie',
            'id',
            'RESTRICT',
            'RESTRICT');
        $this->execute("SET FOREIGN_KEY_CHECKS = 1");
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown() {
	if (is_null($this->db->schema->getTableSchema(
            self::NOME_TABELLA, true))) {
//            echo "Non trovo la tabella '" . self::NOME_TABELLA . "'";
//            return false;
            return true;
	}
        $this->execute("SET FOREIGN_KEY_CHECKS = 0");
        $this->dropForeignKey(self::NOME_TABELLA . '_' . self::NOME_COLONNA,
            self::NOME_TABELLA);
        $this->execute("SET FOREIGN_KEY_CHECKS = 1");
        
        $this->dropColumn(self::NOME_TABELLA, self::NOME_COLONNA);
        return true;
    }
}