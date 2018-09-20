<?php

namespace MageSuite\OrderExport\Services\File;

interface Writer
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