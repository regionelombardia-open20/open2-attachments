<?php

namespace open20\amos\attachments\interfaces;

interface VirusScanInterface
{
    /**
     * Scan a file and return if vulnerable or not
     * @param string $path
     * @return boolean Return true if file is OK, false if Not
     */
    public function scanFile(string $path);

    /**
     * Scan full directory and return the scan status or file list withstatus
     * @param string $path
     * @return bool|array Status of the scan or Array with Per-file status when $returnScannedFiles is true
     */
    public function scanDirectory(string $path, bool $returnScannedFiles = false);


}