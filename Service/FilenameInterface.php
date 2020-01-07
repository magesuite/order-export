<?php
namespace MageSuite\OrderExport\Service;

interface FilenameInterface
{
    /**
     * @param string $incrementId
     * @param string $strategyType
     * @return mixed
     */
    public function getFilename($incrementId = '', $strategyType = '');
}
