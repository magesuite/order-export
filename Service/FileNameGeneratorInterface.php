<?php
namespace MageSuite\OrderExport\Service;

interface FileNameGeneratorInterface
{
    /**
     * @param string $incrementId
     * @param string $strategyType
     * @return mixed
     */
    public function getFileName($incrementId = null, $strategyType = null);
}
