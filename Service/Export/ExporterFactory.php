<?php

namespace MageSuite\OrderExport\Service\Export;

class ExporterFactory
{
    /**
     * @var \MageSuite\OrderExport\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var array
     */
    protected $exporterMap;

    public function __construct(
        \MageSuite\OrderExport\Helper\Configuration $configuration,
        array $exporterMap
    ) {
        $this->configuration = $configuration;
        $this->exporterMap = $exporterMap;
    }

    public function create()
    {
        $exportType = $this->configuration->getExportFileType();

        if (!isset($this->exporterMap[$exportType])) {
            return null;
        }

        $exportStrategy = $this->configuration->getExportStrategy();

        if (!isset($this->exporterMap[$exportType][$exportStrategy])) {
            return null;
        }

        return $this->exporterMap[$exportType][$exportStrategy];
    }
}
