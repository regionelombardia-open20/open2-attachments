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

use function socket_connect;
use function socket_create;

class Pipe extends ClamAV
{
    const CLAMAV_HOST = '/var/run/clamav/clamd.ctl';

    /**
     * @var string
     */
    private $pip;

    /**
     * Pipe constructor.
     *
     * This class can be used to connect to local socket.
     * You need to pass the path to the socket pipe.
     *
     * @param string $pip
     */
    public function __construct(string $pip = self::CLAMAV_HOST)
    {
        $this->pip = $pip;
    }

    /**
     * @return resource
     */
    protected function getSocket()
    {
        $socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
        socket_connect($socket, $this->pip);
        return $socket;
    }
}
