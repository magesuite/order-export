<?php
namespace MageSuite\OrderExport\Services\Export\Strategy;

interface FilenameFormatter
{
    const EXPORT_CONFIGURATION_PATH = 'orderexport/automatic';

    /**
     * @param string $incrementId
     * @param string $strategyType
     * @return mixed
     */
    public function getFilename($incrementId = '', $strategyType = '');
}