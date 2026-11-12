<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   cruscotto-lavoro\platform\common\console\migrations
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

use open20\amos\core\migration\libs\common\MigrationCommon;
use open20\amos\tag\models\Tag;
use yii\db\Migration;

/**
 * Class m180904_084129_add_cl_roles_tags
 */
class m210931_170427_add_column_table_attach_gallery_image extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        try{
            $this->addColumn('attach_gallery_image','aspect_ratio', $this->string()->defaultValue(null)->after('description') );
            $this->addColumn('attach_gallery_request','aspect_ratio_reply', $this->string()->defaultValue(null)->after('aspect_ratio') );
        } catch (\yii\db\Exception $e){

        }

        return true;
    }


    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return true;
    }
}
