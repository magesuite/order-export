<?php

namespace MageSuite\OrderExport\Cron;

class ExportProcessing
{
    /**
     * @var \MageSuite\OrderExport\Service\ExportFactory
     */
    protected $exportFactory;

    /**
     * @var \MageSuite\OrderExport\Helper\Configuration
     */
    protected $configuration;

    public function __construct(
        \MageSuite\OrderExport\Service\ExportFactory $exportFactory,
        \MageSuite\OrderExport\Helper\Configuration $configuration
    ) {
        $this->exportFactory = $exportFactory;
        $this->configuration = $configuration;
    }

    public function execute()
    {
        if (!$this->configuration->isAutomaticExportEnabled()) {
            return;
        }

        $data = [
            'type' => \MageSuite\OrderExport\Helper\Configuration::CRON_EXPORT_TYPE
        ];

        $export = $this->exportFactory->create(['data' => $data]);
        $export->execute();
    }
}
