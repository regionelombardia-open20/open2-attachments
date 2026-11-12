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

namespace open20\amos\attachments\scanners;

use open20\amos\attachments\interfaces\VirusScanInterface;
use yii\base\Component;
use open20\amos\attachments\scanners\ClamAV\Pipe;

class ClamAVScanner extends Component implements VirusScanInterface
{
    /**
     * @inheridoc
     */
    public function scanFile(string $path)
    {
        if (!file_exists($path)) {
            \Yii::warning("Unable to scan file {$path}, not found");
            return true;
        }

        if(file_exists('/tmp/clamd.ctl')){
            $pipe = new Pipe('/tmp/clamd.ctl');
        } else {
            $pipe = new Pipe('/var/run/clamav/clamd.ctl');
        }

        return $pipe->fileScan($path);
    }

    /**
     * @inheridoc
     */
    public function scanDirectory(string $path, bool $returnScannedFiles = false)
    {
        // TODO: Implement scanDirectory() method.
        return null;
    }
}
