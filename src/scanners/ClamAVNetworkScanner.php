<?php
namespace open20\amos\attachments\scanners;

use open20\amos\attachments\interfaces\VirusScanInterface;
use yii\base\Component;
use open20\amos\attachments\scanners\ClamAV\Network;

class ClamAVnetworkScanner extends Component implements VirusScanInterface
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

        //Initialize ClamAV network scanner
        $network = new Network();

        return $network->fileScan($path);
    }

    /**
     * @inheridoc
     */
    public function scanDirectory(string $path, bool $returnScannedFiles = false)
    {
        // TODO: Implement scanDirectory() method.
    }
}
