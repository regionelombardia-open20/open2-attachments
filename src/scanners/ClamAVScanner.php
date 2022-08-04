<?php
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
    }
}
