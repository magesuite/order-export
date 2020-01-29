<?php
namespace MageSuite\OrderExport\Service;

interface UploaderInterface
{
    /**
     * @param string $filePath
     * @return void
     */
    public function upload($filePath);
}
