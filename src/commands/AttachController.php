<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\admin\commands
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

namespace open20\amos\attachments\commands;

use yii\console\Controller;
use Yii;

class AttachController extends Controller {

    /**
     *
     */
    public function actionCheckFiles() {
        $subQuery = \Yii::$app->db->createCommand("select attach_file_id from attach_file_not_exists where deleted_at is null")->rawSql;
        /** @var AmosAdmin $admin */
        $allAttachments = \open20\amos\attachments\models\File::find()
                ->andWhere(new \yii\db\Expression("id not in ($subQuery)"))
                ->limit(1000)
                ->all();
        $now = date('Y-m-d H:i:s');
        $this->log('########### Check iniziato ###########');
        foreach ($allAttachments as $file) {
            $filePath = $file->getPath();
            $ok = 1;
            if (!file_exists($filePath)) {
                $this->log('File con id ' . $file->id . ' non esistente su disco.');
                $ok = 0;
            }
            \Yii::$app->db->createCommand("insert into attach_file_not_exists "
                    . "(attach_file_id, check_ok, created_at, created_by, updated_at, updated_by) "
                    . "value ({$file->id}, $ok, '$now', 1, '$now', 1)")->execute();
        }
        $this->log('########### Check completato ###########');
    }

    /**
     * @param $message
     */
    private function log($message) {
        echo ($message . "\n");
    }
}
