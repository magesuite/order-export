<?php

namespace MageSuite\OrderExport\Service\Writer;

class WriterFactory
{
    /**
     * @var \MageSuite\OrderExport\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var array
     */
    protected $writerMap;

    public function __construct(
        \MageSuite\OrderExport\Helper\Configuration $configuration,
        array $writerMap
    ) {
        $this->configuration = $configuration;
        $this->writerMap = $writerMap;
    }

    public function create()
    {
        $exportType = $this->configuration->getExportFileType();

        if (!isset($this->writerMap[$exportType])) {
            return null;
        }

        return $this->writerMap[$exportType];
    }
}
