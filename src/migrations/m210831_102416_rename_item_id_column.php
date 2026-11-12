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
use yii\db\Schema;

class m210831_102416_rename_item_id_column extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $table = \Yii::$app->db->schema->getTableSchema('attach_file');
        if (isset($table->columns['itemId'])) {
            $this->execute('ALTER TABLE `attach_file` CHANGE COLUMN `itemId` `item_id` INTEGER(11) NULL DEFAULT NULL;');
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $table = \Yii::$app->db->schema->getTableSchema('attach_file');
        if (isset($table->columns['item_id'])) {
            $this->execute('ALTER TABLE `attach_file` CHANGE COLUMN `item_id` `itemId` INTEGER(11) NULL DEFAULT NULL;');
        }
    }
}