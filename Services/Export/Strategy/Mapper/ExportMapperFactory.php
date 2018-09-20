<?php

namespace MageSuite\OrderExport\Services\Export\Strategy\Mapper;


class ExportMapperFactory
{
    /**
     * @var array
     */
    protected $classMappings = [
        'csv' => [
            'grouped' => \MageSuite\OrderExport\Services\Export\Exporter\Strategy\CSV\Grouped::class,
            'separated' => \MageSuite\OrderExport\Services\Export\Exporter\Strategy\CSV\Separated::class
        ],
        'xml' => [
            'grouped' => \MageSuite\OrderExport\Services\Export\Exporter\Strategy\XML\Grouped::class,
            'separated' => \MageSuite\OrderExport\Services\Export\Exporter\Strategy\XML\Separated::class
        ]
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
     * @return \MageSuite\OrderExport\Services\Export\Exporter
     */
    public function create($exporterType, string $exporterName)
    {
        if (!isset($this->classMappings[$exporterType][$exporterName])) {
            return null;
        }

        return $this->objectManager->create($this->classMappings[$exporterType][$exporterName]);
    }
}