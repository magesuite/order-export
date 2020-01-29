<?php

namespace MageSuite\OrderExport\Service\Writer;

class Writer
{
    protected $fileHandler;

    public function openFile($filePath)
    {
        $this->ensureTargetFolderExist($filePath);
        $this->fileHandler = fopen($filePath, 'w');
    }

    public function closeFile()
    {
        fclose($this->fileHandler);
    }

    protected function ensureTargetFolderExist($filePath)
    {
        $dir = dirname($filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}
