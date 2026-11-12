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
class m150127_040544_add_attachments extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('attach_file', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'model' => Schema::TYPE_STRING . ' NOT NULL',
            'attribute' => Schema::TYPE_STRING . ' NOT NULL',
            'item_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'hash' => Schema::TYPE_STRING . ' NOT NULL',
            'size' => Schema::TYPE_INTEGER . ' NOT NULL',
            'type' => Schema::TYPE_STRING . ' NOT NULL',
            'mime' => Schema::TYPE_STRING . ' NOT NULL',
            'is_main' => Schema::TYPE_BOOLEAN . ' DEFAULT 0',
            'date_upload' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'sort' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 1'
        ]);

        $this->createIndex('file_model', 'attach_file', 'model');
        $this->createIndex('file_item_id', 'attach_file', 'item_id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('attach_file');
    }
}
