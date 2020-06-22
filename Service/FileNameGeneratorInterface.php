<?php
namespace MageSuite\OrderExport\Service;

interface FileNameGeneratorInterface
{
    /**
     * @param string $incrementId
     * @param string $strategyType
     * @param string $entityId
     * @return mixed
     */
    public function getFileName($incrementId = null, $strategyType = null, $entityId = null);
}
