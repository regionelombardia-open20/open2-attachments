<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\attachments\migrations
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
 * Class m210923_100611_add_columns
 */
class m210923_100611_add_columns extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if (!$this->db->getTableSchema('attach_file_refs')->getColumn('s3_url')) {
            $this->addColumn('attach_file_refs', 's3_url', $this->text()->defaultValue(null));
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        if (!$this->db->getTableSchema('attach_file_refs')->getColumn('s3_url')) {
            $this->dropColumn('attach_file_refs', 's3_url');
        }

        return true;
    }
}
 