<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\attachments
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

/**
 * Class m150127_040544_add_attachments
 */
class m170721_111144_add_attachments_refs extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('attach_file_refs', [
            'id' => Schema::TYPE_PK,
            'hash' => Schema::TYPE_STRING . ' NOT NULL',
            'attach_file_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'model' => Schema::TYPE_STRING . ' NOT NULL',
            'item_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'attribute' => Schema::TYPE_STRING . ' NOT NULL',
            'crop' => Schema::TYPE_STRING,
            'date_upload' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'is_main' => Schema::TYPE_BOOLEAN . ' DEFAULT 0',
            'sort' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 1',
        ]);

        $this->createIndex('attach_file_refs_hash', 'attach_file_refs', 'hash');
        $this->createIndex('attach_file_refs_item', 'attach_file_refs', 'item_id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('attach_file_refs');
    }
}
