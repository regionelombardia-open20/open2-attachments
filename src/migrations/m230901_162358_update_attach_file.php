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