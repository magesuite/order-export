<?php

namespace MageSuite\OrderExport\Service\Writer;

interface WriterInterface
{
    /**
     * @param $filePath
     */
    public function openFile($filePath);

    public function closeFile();

    /**
     * @param array $data
     */
    public function write($data);
}
