<?php

namespace MageSuite\OrderExport\Cron;

class PeriodicalExporter
{
    /**
     * @var \MageSuite\OrderExport\Service\ExporterFactory
     */
    protected $exporterFactory;

    /**
     * @var \MageSuite\OrderExport\Helper\Configuration
     */
    protected $configuration;

    public function __construct(
        \MageSuite\OrderExport\Service\ExporterFactory $exporterFactory,
        \MageSuite\OrderExport\Helper\Configuration $configuration
    ) {
        $this->exporterFactory = $exporterFactory;
        $this->configuration = $configuration;
    }

    public function execute()
    {
        if (!$this->configuration->isPeriodicalExportEnabled()) {
            return;
        }

        $data = [
            'type' => \MageSuite\OrderExport\Helper\Configuration::CRON_EXPORT_TYPE
        ];

        $exporter = $this->exporterFactory->create(['data' => $data]);
        $exporter->execute();
    }
}
