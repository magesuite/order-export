<?php

namespace MageSuite\OrderExport\Services\Export;


abstract class AbstractExporterFactory
{
    /**
     * @var array
     */
    protected $classMappings = [
        'csv' => \MageSuite\OrderExport\Services\Export\Exporter\CSV::class,
        'xml' => \MageSuite\OrderExport\Services\Export\Exporter\XML::class,
    ];

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager
    )
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param string $exporterName
     * @return Exporter
     */
    public function create(string $exporterName)
    {
        if (!isset($this->classMappings[$exporterName])) {
            return null;
        }

        return $this->objectManager->create($this->classMappings[$exporterName]);
    }
}