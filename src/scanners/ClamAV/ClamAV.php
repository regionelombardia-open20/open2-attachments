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

namespace open20\amos\attachments\scanners\ClamAV;

use function end;
use function explode;
use function socket_close;
use function socket_recv;
use function socket_send;
use function strlen;
use function trim;

abstract class ClamAV
{
    /**
     * @var int
     */
    const CLAMAV_MAX = 20000;

    /**
     * @return resource
     */
    abstract protected function getSocket();

    /**
     * @param $command
     * @return null
     */
    private function sendCommand($command)
    {
        $return = null;

        $socket = $this->getSocket();

        socket_send($socket, $command, strlen($command), 0);
        socket_recv($socket, $return, self::CLAMAV_MAX, 0);
        socket_close($socket);

        return $return;
    }

    /**
     * Ping ClamAV Socket
     *
     * Check if ClamAV is up and responsive
     *
     * @return bool
     */
    public function ping(): bool
    {
        $return = $this->sendCommand('PING');
        return trim($return) === 'PONG';
    }

    /**
     * Check ClamAV Version
     *
     * @return string
     */
    public function version(): string
    {
        return trim($this->sendCommand('VERSION'));
    }

    /**
     * Reload ClamAV virus databases
     *
     * @return null
     */
    public function reload()
    {
        return $this->sendCommand('RELOAD');
    }

    /**
     * Shutdown ClamAV and preform a clean exit
     *
     * @return null
     */
    public function shutdown()
    {
        return $this->sendCommand('SHUTDOWN');
    }

    /**
     * Scan a file or a directory (recursively) with archive support
     *  enabled (if not disabled in clamd.conf). A full path is required.
     *
     * @param string $file
     * @return bool return true if file is OK or false if not
     */
    public function fileScan(string $file): bool
    {
        $out = $this->sendCommand('SCAN ' . $file);

        $out = explode(':', $out);
        $stats = end($out);

        $result = trim($stats);

        return ($result === 'OK');
    }

    /**
     * Scan file or directory (recursively) with archive support
     *  enabled and don't stop the scanning when a virus is found.
     *
     * @param string $file
     * @return array
     */
    public function continueScan(string $file): array
    {
        $return = [];

        foreach (explode("\n", trim($this->sendCommand('CONTSCAN ' . $file))) as $results) {
            list($file, $stats) = explode(':', $results);
            $return[] = ['file' => $file, 'stats' => trim($stats)];
        }

        return $return;
    }
}
