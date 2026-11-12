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

use RuntimeException;
use function socket_connect;
use function socket_create;

class Network extends ClamAV
{
    const CLAMAV_HOST = '127.0.0.1';
    const CLAMAV_PORT = 3310;

    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * Network constructor
     *
     * You need to pass the host address and the port the the server
     *
     * @param string $host
     * @param int $port
     */
    public function __construct(string $host = self::CLAMAV_HOST, int $port = self::CLAMAV_PORT)
    {
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * @return resource
     * @throws RuntimeException
     */
    protected function getSocket()
    {
        $socket = @socket_create(AF_INET, SOCK_STREAM, 0);
        $status = @socket_connect($socket, $this->host, $this->port);

        if (!$status) {
            throw new RuntimeException('Unable to connect to ClamAV server');
        }
        return $socket;
    }
}
